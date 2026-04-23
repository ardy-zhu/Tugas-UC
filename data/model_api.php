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
            $modelId = isset($_GET['model_id']) ? (int) $_GET['model_id'] : 0;

            if ($modelId > 0) {
                $stmt = mysqli_prepare($koneksi, 'SELECT model_id, nama, panjang_model FROM model WHERE model_id = ?');
                mysqli_stmt_bind_param($stmt, 'i', $modelId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);

                if (!$row) {
                    send_json(404, [
                        'success' => false,
                        'message' => 'Data model tidak ditemukan.'
                    ]);
                }

                send_json(200, [
                    'success' => true,
                    'data' => $row
                ]);
            }

            $result = mysqli_query($koneksi, 'SELECT model_id, nama, panjang_model FROM model ORDER BY model_id ASC');
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
            $panjangModel = isset($data['panjang_model']) ? (float) $data['panjang_model'] : null;

            if ($nama === '' || $panjangModel === null) {
                send_json(422, [
                    'success' => false,
                    'message' => 'Field nama dan panjang_model wajib diisi.'
                ]);
            }

            $stmt = mysqli_prepare($koneksi, 'INSERT INTO model (nama, panjang_model) VALUES (?, ?)');
            mysqli_stmt_bind_param($stmt, 'sd', $nama, $panjangModel);

            if (!mysqli_stmt_execute($stmt)) {
                send_json(500, [
                    'success' => false,
                    'message' => 'Gagal menambahkan data model.'
                ]);
            }

            send_json(201, [
                'success' => true,
                'message' => 'Data model berhasil ditambahkan.',
                'data' => [
                    'model_id' => mysqli_insert_id($koneksi),
                    'nama' => $nama,
                    'panjang_model' => $panjangModel
                ]
            ]);
            break;

        case 'PUT':
            $modelId = isset($data['model_id']) ? (int) $data['model_id'] : 0;
            $nama = trim((string) ($data['nama'] ?? ''));
            $panjangModel = isset($data['panjang_model']) ? (float) $data['panjang_model'] : null;

            if ($modelId <= 0 || $nama === '' || $panjangModel === null) {
                send_json(422, [
                    'success' => false,
                    'message' => 'Field model_id, nama, dan panjang_model wajib diisi.'
                ]);
            }

            $stmt = mysqli_prepare($koneksi, 'UPDATE model SET nama = ?, panjang_model = ? WHERE model_id = ?');
            mysqli_stmt_bind_param($stmt, 'sdi', $nama, $panjangModel, $modelId);

            if (!mysqli_stmt_execute($stmt)) {
                send_json(500, [
                    'success' => false,
                    'message' => 'Gagal mengubah data model.'
                ]);
            }

            if (mysqli_stmt_affected_rows($stmt) < 1) {
                send_json(404, [
                    'success' => false,
                    'message' => 'Data model tidak ditemukan atau tidak ada perubahan.'
                ]);
            }

            send_json(200, [
                'success' => true,
                'message' => 'Data model berhasil diperbarui.',
                'data' => [
                    'model_id' => $modelId,
                    'nama' => $nama,
                    'panjang_model' => $panjangModel
                ]
            ]);
            break;

        case 'DELETE':
            $modelId = isset($data['model_id']) ? (int) $data['model_id'] : (isset($_GET['model_id']) ? (int) $_GET['model_id'] : 0);

            if ($modelId <= 0) {
                send_json(422, [
                    'success' => false,
                    'message' => 'Field model_id wajib diisi.'
                ]);
            }

            $stmt = mysqli_prepare($koneksi, 'DELETE FROM model WHERE model_id = ?');
            mysqli_stmt_bind_param($stmt, 'i', $modelId);

            if (!mysqli_stmt_execute($stmt)) {
                send_json(500, [
                    'success' => false,
                    'message' => 'Gagal menghapus data model.'
                ]);
            }

            if (mysqli_stmt_affected_rows($stmt) < 1) {
                send_json(404, [
                    'success' => false,
                    'message' => 'Data model tidak ditemukan.'
                ]);
            }

            send_json(200, [
                'success' => true,
                'message' => 'Data model berhasil dihapus.'
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
