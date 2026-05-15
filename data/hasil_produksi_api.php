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
        'error' => $e->getMessage()
    ]);
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'GET') {
    send_json(405, [
        'success' => false,
        'message' => 'Method tidak didukung. Gunakan GET.'
    ]);
}

$bahanId = isset($_GET['bahan_id']) ? (int) $_GET['bahan_id'] : 0;
$modelId = isset($_GET['model_id']) ? (int) $_GET['model_id'] : 0;

try {
    if ($bahanId <= 0 || $modelId <= 0) {
        $bahanRows = [];
        $modelRows = [];

        $bahanResult = mysqli_query($koneksi, 'SELECT bahan_id, nama, panjang_bahan FROM bahan_baku ORDER BY bahan_id DESC');
        while ($row = mysqli_fetch_assoc($bahanResult)) {
            $bahanRows[] = $row;
        }

        $modelResult = mysqli_query($koneksi, 'SELECT model_id, nama, panjang_model FROM model ORDER BY model_id ASC');
        while ($row = mysqli_fetch_assoc($modelResult)) {
            $modelRows[] = $row;
        }

        send_json(200, [
            'success' => true,
            'message' => 'Sertakan bahan_id dan model_id untuk menghitung hasil produksi.',
            'data' => [
                'bahan_baku' => $bahanRows,
                'model' => $modelRows,
                'contoh_url' => 'hasil_produksi_api.php?bahan_id=1&model_id=1'
            ]
        ]);
    }

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
            'message' => 'Panjang model harus lebih dari 0 agar bisa dihitung.'
        ]);
    }

    $hasilTeoritis = $panjangBahan / $panjangModel;
    $jumlahHasil = (int) floor($hasilTeoritis);
    $sisaPanjang = $panjangBahan - ($jumlahHasil * $panjangModel);

    send_json(200, [
        'success' => true,
        'data' => [
            'bahan_baku' => $bahan,
            'model' => $model,
            'perhitungan' => [
                'rumus' => 'panjang_bahan_baku / panjang_model',
                'hasil_teoritis' => round($hasilTeoritis, 4),
                'jumlah_hasil' => $jumlahHasil,
                'sisa_panjang' => round($sisaPanjang, 4)
            ]
        ]
    ]);
} catch (Throwable $e) {
    send_json(500, [
        'success' => false,
        'message' => 'Terjadi kesalahan pada server.',
        'error' => $e->getMessage()
    ]);
}
