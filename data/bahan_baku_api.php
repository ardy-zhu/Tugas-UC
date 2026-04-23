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

function get_request_data(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $rawBody = file_get_contents('php://input');

    if (stripos($contentType, 'application/json') !== false && !empty($rawBody)) {
        $decoded = json_decode($rawBody, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
    }

    if (!empty($_POST)) {
        return $_POST;
    }

    if (!empty($rawBody)) {
        parse_str($rawBody, $parsed);
        if (is_array($parsed)) {
            return $parsed;
        }
    }

    return [];
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$data = get_request_data();

if ($method === 'POST' && isset($data['_method'])) {
    $override = strtoupper((string) $data['_method']);
    if (in_array($override, ['PUT', 'DELETE'], true)) {
        $method = $override;
    }
}

try {
    switch ($method) {
        case 'GET':
            $bahanId = isset($_GET['bahan_id']) ? (int) $_GET['bahan_id'] : 0;

            if ($bahanId > 0) {
                $stmt = mysqli_prepare($koneksi, 'SELECT bahan_id, nama, panjang_bahan FROM bahan_baku WHERE bahan_id = ?');
                mysqli_stmt_bind_param($stmt, 'i', $bahanId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);

                if (!$row) {
                    send_json(404, [
                        'success' => false,
                        'message' => 'Data bahan baku tidak ditemukan.'
                    ]);
                }

                send_json(200, [
                    'success' => true,
                    'data' => $row
                ]);
            }

            $result = mysqli_query($koneksi, 'SELECT bahan_id, nama, panjang_bahan FROM bahan_baku ORDER BY bahan_id DESC');
            $rows = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }

            send_json(200, [
                'success' => true,
                'data' => $rows
            ]);
            break;

        case 'POST':
            $nama = trim((string) ($data['nama'] ?? ''));
            $panjangBahan = isset($data['panjang_bahan']) ? (float) $data['panjang_bahan'] : null;

            if ($nama === '' || $panjangBahan === null) {
                send_json(422, [
                    'success' => false,
                    'message' => 'Field nama dan panjang_bahan wajib diisi.'
                ]);
            }

            $stmt = mysqli_prepare($koneksi, 'INSERT INTO bahan_baku (nama, panjang_bahan) VALUES (?, ?)');
            mysqli_stmt_bind_param($stmt, 'sd', $nama, $panjangBahan);

            if (!mysqli_stmt_execute($stmt)) {
                send_json(500, [
                    'success' => false,
                    'message' => 'Gagal menambahkan data bahan baku.'
                ]);
            }

            send_json(201, [
                'success' => true,
                'message' => 'Data bahan baku berhasil ditambahkan.',
                'data' => [
                    'bahan_id' => mysqli_insert_id($koneksi),
                    'nama' => $nama,
                    'panjang_bahan' => $panjangBahan
                ]
            ]);
            break;

        case 'PUT':
            $bahanId = isset($data['bahan_id']) ? (int) $data['bahan_id'] : 0;
            $nama = trim((string) ($data['nama'] ?? ''));
            $panjangBahan = isset($data['panjang_bahan']) ? (float) $data['panjang_bahan'] : null;

            if ($bahanId <= 0 || $nama === '' || $panjangBahan === null) {
                send_json(422, [
                    'success' => false,
                    'message' => 'Field bahan_id, nama, dan panjang_bahan wajib diisi.'
                ]);
            }

            $stmt = mysqli_prepare($koneksi, 'UPDATE bahan_baku SET nama = ?, panjang_bahan = ? WHERE bahan_id = ?');
            mysqli_stmt_bind_param($stmt, 'sdi', $nama, $panjangBahan, $bahanId);

            if (!mysqli_stmt_execute($stmt)) {
                send_json(500, [
                    'success' => false,
                    'message' => 'Gagal mengubah data bahan baku.'
                ]);
            }

            if (mysqli_stmt_affected_rows($stmt) < 1) {
                send_json(404, [
                    'success' => false,
                    'message' => 'Data bahan baku tidak ditemukan atau tidak ada perubahan.'
                ]);
            }

            send_json(200, [
                'success' => true,
                'message' => 'Data bahan baku berhasil diperbarui.',
                'data' => [
                    'bahan_id' => $bahanId,
                    'nama' => $nama,
                    'panjang_bahan' => $panjangBahan
                ]
            ]);
            break;

        case 'DELETE':
            $bahanId = isset($data['bahan_id']) ? (int) $data['bahan_id'] : (isset($_GET['bahan_id']) ? (int) $_GET['bahan_id'] : 0);

            if ($bahanId <= 0) {
                send_json(422, [
                    'success' => false,
                    'message' => 'Field bahan_id wajib diisi.'
                ]);
            }

            $stmt = mysqli_prepare($koneksi, 'DELETE FROM bahan_baku WHERE bahan_id = ?');
            mysqli_stmt_bind_param($stmt, 'i', $bahanId);

            if (!mysqli_stmt_execute($stmt)) {
                send_json(500, [
                    'success' => false,
                    'message' => 'Gagal menghapus data bahan baku.'
                ]);
            }

            if (mysqli_stmt_affected_rows($stmt) < 1) {
                send_json(404, [
                    'success' => false,
                    'message' => 'Data bahan baku tidak ditemukan.'
                ]);
            }

            send_json(200, [
                'success' => true,
                'message' => 'Data bahan baku berhasil dihapus.'
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
        'error' => $e->getMessage()
    ]);
}
