<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Bahan Baku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 700px;
        }

        .form-group {
            margin: 15px 0;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        button {
            padding: 10px 20px;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-save {
            background-color: #1f7a1f;
        }

        .btn-delete {
            background-color: #b42318;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-delete-row {
            background-color: #b42318;
            padding: 6px 10px;
            font-size: 13px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
        }

        .btn-edit-row {
            background-color: #1d4ed8;
            padding: 6px 10px;
            font-size: 13px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            margin-right: 6px;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Form Bahan Baku</h1>

        <div id="messageBox" class="alert" style="display:none;"></div>

        <form id="bahanForm">
            <input type="hidden" id="bahan_id" name="bahan_id">
            <div class="form-group">
                <label for="nama">Bahan</label>
                <input type="text" id="nama" name="nama" required>
            </div>

            <div class="form-group">
                <label for="panjang">Panjang</label>
                <input type="number" id="panjang" name="panjang" step="0.01" required>
            </div>

            <div class="button-group">
                <button class="btn-save" type="submit">Save</button>
            </div>
        </form>

        <h2>Daftar Bahan Baku Tersimpan</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bahan</th>
                    <th>Panjang</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="bahanTableBody">
                <tr>
                    <td colspan="4">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        const API_URL = 'http://localhost/afl3wp/data/bahan_baku_api.php';
        const form = document.getElementById('bahanForm');
        const tableBody = document.getElementById('bahanTableBody');
        const messageBox = document.getElementById('messageBox');

        function showMessage(type, text) {
            messageBox.className = 'alert ' + (type === 'error' ? 'alert-error' : 'alert-success');
            messageBox.textContent = text;
            messageBox.style.display = 'block';
        }

        function clearMessage() {
            messageBox.style.display = 'none';
            messageBox.textContent = '';
        }

        async function requestJson(url, options) {
            const response = await fetch(url, options);
            const rawText = await response.text();
            let data = null;

            try {
                data = rawText ? JSON.parse(rawText) : null;
            } catch (error) {
                const snippet = rawText ? rawText.slice(0, 180) : '';
                throw new Error('Respons server bukan JSON valid. ' + snippet);
            }

            return {
                response,
                data
            };
        }

        async function loadBahanBaku() {
            try {
                const {
                    response,
                    data
                } = await requestJson(API_URL);

                if (!response.ok || !data || !Array.isArray(data.data)) {
                    throw new Error((data && data.message) ? data.message : 'Gagal memuat daftar bahan baku.');
                }

                if (data.data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="4">Belum ada data bahan baku.</td></tr>';
                    return;
                }

                const sortedData = [...data.data].sort((a, b) => {
                    const idA = Number(a.bahan_id ?? a.id ?? 0);
                    const idB = Number(b.bahan_id ?? b.id ?? 0);
                    return idA - idB;
                });

                tableBody.innerHTML = sortedData.map((item) => {
                    const id = item.bahan_id ?? item.id ?? '-';
                    const nama = item.nama ?? '-';
                    const panjang = item.panjang_bahan ?? item.panjang ?? '-';
                    const rowId = item.bahan_id ?? item.id;
                    const safeNama = String(nama).replace(/"/g, '&quot;');
                    const rowActions = rowId ?
                        '<button class="btn-edit-row" type="button" data-edit-id="' + String(rowId) + '" data-edit-nama="' + safeNama + '" data-edit-panjang="' + String(panjang) + '">Edit</button>' +
                        '<button class="btn-delete-row" type="button" data-id="' + String(rowId) + '">Delete</button>' : '-';

                    return '<tr>' +
                        '<td>' + String(id) + '</td>' +
                        '<td>' + String(nama) + '</td>' +
                        '<td>' + String(panjang) + '</td>' +
                        '<td>' + rowActions + '</td>' +
                        '</tr>';
                }).join('');
            } catch (err) {
                tableBody.innerHTML = '<tr><td colspan="4">Gagal memuat data.</td></tr>';
                showMessage('error', err.message || 'Terjadi kesalahan saat memuat data.');
            }
        }

        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            clearMessage();

            const nama = document.getElementById('nama').value.trim();
            const panjang = document.getElementById('panjang').value.trim();
            const bahanId = document.getElementById('bahan_id').value.trim();

            if (!nama || !panjang) {
                showMessage('error', 'Field bahan dan panjang wajib diisi.');
                return;
            }

            const isUpdate = bahanId !== '';
            const payload = {
                nama: nama,
                panjang_bahan: Number(panjang)
            };

            if (isUpdate) {
                payload.bahan_id = Number(bahanId);
            }

            try {
                const {
                    response,
                    data
                } = await requestJson(API_URL, {
                    method: isUpdate ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    throw new Error(data.message || 'Gagal menyimpan data bahan baku.');
                }

                showMessage('success', data.message || 'Data bahan baku berhasil diproses.');
                form.reset();
                document.getElementById('bahan_id').value = '';
                await loadBahanBaku();
            } catch (err) {
                showMessage('error', err.message || 'Terjadi kesalahan saat menyimpan data.');
            }
        });

        tableBody.addEventListener('click', async function(event) {
            const editButton = event.target.closest('[data-edit-id]');
            if (editButton) {
                document.getElementById('bahan_id').value = editButton.getAttribute('data-edit-id') || '';
                document.getElementById('nama').value = editButton.getAttribute('data-edit-nama') || '';
                document.getElementById('panjang').value = editButton.getAttribute('data-edit-panjang') || '';
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }

            const button = event.target.closest('.btn-delete-row');
            if (!button) {
                return;
            }

            const bahanId = button.getAttribute('data-id');
            if (!bahanId) {
                showMessage('error', 'ID bahan baku tidak valid.');
                return;
            }

            if (!confirm('Yakin ingin menghapus data ini?')) {
                return;
            }

            clearMessage();

            try {
                const {
                    response,
                    data
                } = await requestJson(API_URL, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        bahan_id: Number(bahanId)
                    })
                });

                if (!response.ok) {
                    throw new Error(data.message || 'Gagal menghapus data bahan baku.');
                }

                showMessage('success', data.message || 'Data bahan baku berhasil dihapus.');
                if (document.getElementById('bahan_id').value === String(bahanId)) {
                    form.reset();
                    document.getElementById('bahan_id').value = '';
                }
                await loadBahanBaku();
            } catch (err) {
                showMessage('error', err.message || 'Terjadi kesalahan saat menghapus data.');
            }
        });

        loadBahanBaku();
    </script>
</body>

</html>