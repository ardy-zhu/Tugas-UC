<?php

header('Content-Type: application/json; charset=utf-8');

function send_json(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

try {
    require_once __DIR__ . '/../koneksi.php';
} catch (Throwable $e) {
    send_json(500, [
        'success' => false,
        'message' => 'Koneksi database gagal.',
        'error'   => $e->getMessage()
    ]);
}

function get_request_data(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $rawBody     = file_get_contents('php://input');

    if (stripos($contentType, 'application/json') !== false && $rawBody !== '') {
        $decoded = json_decode($rawBody, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
    }

    if (!empty($_POST)) {
        return $_POST;
    }

    if ($rawBody !== '') {
        parse_str($rawBody, $parsed);
        if (is_array($parsed)) {
            return $parsed;
        }
    }

    return [];
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$data   = get_request_data();

try {
    switch ($method) {
        case 'GET':
            // Bahan baku yang belum diproses (belum ada di hasil_produksi)
            $availableBahan = [];
            $resultAvail = mysqli_query(
                $koneksi,
                'SELECT bahan_id, nama, panjang_bahan FROM bahan_baku
                 WHERE bahan_id NOT IN (SELECT bahan_id FROM hasil_produksi)
                 ORDER BY bahan_id ASC'
            );
            while ($row = mysqli_fetch_assoc($resultAvail)) {
                $availableBahan[] = $row;
            }

            // Semua model
            $models = [];
            $resultModel = mysqli_query(
                $koneksi,
                'SELECT model_id, nama, panjang_model FROM model ORDER BY model_id ASC'
            );
            while ($row = mysqli_fetch_assoc($resultModel)) {
                $models[] = $row;
            }

            // Riwayat proses pemotongan
            $history = [];
            $resultHistory = mysqli_query(
                $koneksi,
                'SELECT hp.produksi_id, b.nama AS nama_bahan, m.nama AS nama_model,
                        b.panjang_bahan, m.panjang_model, hp.qty, hp.tanggal
                 FROM hasil_produksi hp
                 JOIN bahan_baku b ON b.bahan_id = hp.bahan_id
                 JOIN model m ON m.model_id = hp.model_id
                 ORDER BY hp.produksi_id DESC'
            );
            while ($row = mysqli_fetch_assoc($resultHistory)) {
                $history[] = $row;
            }

            send_json(200, [
                'success' => true,
                'data'    => [
                    'bahan_baku_tersedia' => $availableBahan,
                    'model'              => $models,
                    'riwayat'            => $history
                ]
            ]);
            break;

        case 'POST':
            $bahanId = isset($data['bahan_id']) ? (int) $data['bahan_id'] : 0;
            $modelId = isset($data['model_id']) ? (int) $data['model_id'] : 0;

            if ($bahanId <= 0 || $modelId <= 0) {
                send_json(422, [
                    'success' => false,
                    'message' => 'bahan_id dan model_id wajib diisi.'
                ]);
            }

            // Cek apakah kombinasi bahan_id + model_id sudah pernah diproses
            $stmtCek = mysqli_prepare($koneksi, 'SELECT produksi_id FROM hasil_produksi WHERE bahan_id = ? AND model_id = ? LIMIT 1');
            mysqli_stmt_bind_param($stmtCek, 'ii', $bahanId, $modelId);
            mysqli_stmt_execute($stmtCek);
            mysqli_stmt_store_result($stmtCek);
            if (mysqli_stmt_num_rows($stmtCek) > 0) {
                send_json(409, [
                    'success' => false,
                    'message' => 'Kombinasi bahan baku dan model ini sudah pernah diproses.'
                ]);
            }
            mysqli_stmt_close($stmtCek);

            // Ambil data bahan baku
            $stmtBahan = mysqli_prepare($koneksi, 'SELECT bahan_id, nama, panjang_bahan FROM bahan_baku WHERE bahan_id = ?');
            mysqli_stmt_bind_param($stmtBahan, 'i', $bahanId);
            mysqli_stmt_execute($stmtBahan);
            $bahan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtBahan));
            if (!$bahan) {
                send_json(404, [
                    'success' => false,
                    'message' => 'Data bahan baku tidak ditemukan.'
                ]);
            }

            // Ambil data model
            $stmtModel = mysqli_prepare($koneksi, 'SELECT model_id, nama, panjang_model FROM model WHERE model_id = ?');
            mysqli_stmt_bind_param($stmtModel, 'i', $modelId);
            mysqli_stmt_execute($stmtModel);
            $model = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtModel));
            if (!$model) {
                send_json(404, [
                    'success' => false,
                    'message' => 'Data model tidak ditemukan.'
                ]);
            }

            $panjangBahan = (float) $bahan['panjang_bahan'];
            $panjangModel = (float) $model['panjang_model'];

            if ($panjangModel <= 0) {
                send_json(422, [
                    'success' => false,
                    'message' => 'Panjang model harus lebih dari 0.'
                ]);
            }

            $qty     = (int) floor($panjangBahan / $panjangModel);
            $tanggal = time();

            $stmtInsert = mysqli_prepare(
                $koneksi,
                'INSERT INTO hasil_produksi (model_id, bahan_id, qty, tanggal) VALUES (?, ?, ?, ?)'
            );
            mysqli_stmt_bind_param($stmtInsert, 'iiii', $modelId, $bahanId, $qty, $tanggal);

            if (!mysqli_stmt_execute($stmtInsert)) {
                send_json(500, [
                    'success' => false,
                    'message' => 'Gagal menyimpan data proses pemotongan.'
                ]);
            }

            $produksiId = mysqli_insert_id($koneksi);

            send_json(201, [
                'success' => true,
                'message' => 'Proses pemotongan berhasil disimpan.',
                'data'    => [
                    'produksi_id'   => $produksiId,
                    'nama_bahan'    => $bahan['nama'],
                    'nama_model'    => $model['nama'],
                    'panjang_bahan' => $panjangBahan,
                    'panjang_model' => $panjangModel,
                    'qty'           => $qty,
                    'tanggal'       => $tanggal
                ]
            ]);
            break;

        case 'DELETE':
            $produksiId = isset($data['produksi_id']) ? (int) $data['produksi_id'] : 0;

            if ($produksiId <= 0) {
                send_json(422, [
                    'success' => false,
                    'message' => 'produksi_id wajib diisi.'
                ]);
            }

            $stmtDelete = mysqli_prepare($koneksi, 'DELETE FROM hasil_produksi WHERE produksi_id = ? LIMIT 1');
            mysqli_stmt_bind_param($stmtDelete, 'i', $produksiId);
            mysqli_stmt_execute($stmtDelete);

            if (mysqli_stmt_affected_rows($stmtDelete) <= 0) {
                send_json(404, [
                    'success' => false,
                    'message' => 'Riwayat proses pemotongan tidak ditemukan.'
                ]);
            }

            mysqli_stmt_close($stmtDelete);

            send_json(200, [
                'success' => true,
                'message' => 'Riwayat proses pemotongan berhasil dihapus.'
            ]);
            break;

        default:
            send_json(405, [
                'success' => false,
                'message' => 'Method tidak didukung.'
            ]);
    }
} catch (Throwable $e) {
    send_json(500, [
        'success' => false,
        'message' => 'Terjadi kesalahan pada server.',
        'error'   => $e->getMessage()
    ]);
}
