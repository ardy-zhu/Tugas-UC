<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Model</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 760px;
        }

        .form-group {
            margin: 12px 0;
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
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            padding: 10px 16px;
        }

        .btn-save {
            background-color: #1f7a1f;
        }

        .btn-clear {
            background-color: #6c757d;
        }

        .btn-delete-row {
            background-color: #b42318;
            padding: 6px 10px;
            font-size: 13px;
        }

        .btn-edit-row {
            background-color: #1d4ed8;
            padding: 6px 10px;
            font-size: 13px;
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Form Model</h1>

        <div id="messageBox" class="alert" style="display:none;"></div>

        <form id="modelForm">
            <input type="hidden" id="model_id" name="model_id">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" required>
            </div>

            <div class="form-group">
                <label for="panjang">Panjang</label>
                <input type="number" id="panjang" name="panjang" required>
            </div>

            <div class="button-group">
                <button class="btn-save" type="submit">Save</button>
                <button class="btn-clear" type="button" id="clearBtn">Clear</button>
            </div>
        </form>

        <h2>Daftar Model</h2>
        <table>
            <thead>
                <tr>
                    <th>Model ID</th>
                    <th>Nama</th>
                    <th>Panjang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="modelTableBody">
                <tr>
                    <td colspan="4">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        const API_URL = 'http://localhost/afl3wp/data/model_api.php';
        const form = document.getElementById('modelForm');
        const tableBody = document.getElementById('modelTableBody');
        const messageBox = document.getElementById('messageBox');
        const clearBtn = document.getElementById('clearBtn');

        function showMessage(type, text) {
            messageBox.className = 'alert ' + (type === 'error' ? 'alert-error' : 'alert-success');
            messageBox.textContent = text;
            messageBox.style.display = 'block';
        }

        function clearMessage() {
            messageBox.style.display = 'none';
            messageBox.textContent = '';
        }

        function clearForm() {
            form.reset();
            document.getElementById('model_id').value = '';
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

        async function loadModelList() {
            try {
                const {
                    response,
                    data
                } = await requestJson(API_URL);

                if (!response.ok || !data || !Array.isArray(data.data)) {
                    throw new Error((data && data.message) ? data.message : 'Gagal memuat data model.');
                }

                if (data.data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="4">Belum ada data model.</td></tr>';
                    return;
                }

                tableBody.innerHTML = data.data.map((item) => {
                    const modelId = item.model_id ?? '-';
                    const nama = item.nama ?? '-';
                    const panjang = item.panjang_model ?? item.panjang ?? '-';
                    const actionHtml = (item.model_id) ?
                        '<button class="btn-edit-row" type="button" data-edit-id="' + String(item.model_id) + '" data-edit-nama="' + String(nama).replace(/"/g, '&quot;') + '" data-edit-panjang="' + String(panjang) + '">Edit</button>' +
                        '<button class="btn-delete-row" type="button" data-delete-id="' + String(item.model_id) + '">Delete</button>' : '-';

                    return '<tr>' +
                        '<td>' + String(modelId) + '</td>' +
                        '<td>' + String(nama) + '</td>' +
                        '<td>' + String(panjang) + '</td>' +
                        '<td>' + actionHtml + '</td>' +
                        '</tr>';
                }).join('');
            } catch (err) {
                tableBody.innerHTML = '<tr><td colspan="4">Gagal memuat data model.</td></tr>';
                showMessage('error', err.message || 'Terjadi kesalahan saat memuat data model.');
            }
        }

        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            clearMessage();

            const modelIdInput = document.getElementById('model_id').value.trim();
            const nama = document.getElementById('nama').value.trim();
            const panjang = document.getElementById('panjang').value.trim();

            if (!nama || !panjang) {
                showMessage('error', 'Field nama dan panjang wajib diisi.');
                return;
            }

            const isUpdate = modelIdInput !== '';
            const payload = {
                nama: nama,
                panjang_model: Number(panjang)
            };

            if (isUpdate) {
                payload.model_id = Number(modelIdInput);
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
                    throw new Error((data && data.message) ? data.message : 'Gagal menyimpan data model.');
                }

                showMessage('success', (data && data.message) ? data.message : 'Data model berhasil diproses.');
                clearForm();
                await loadModelList();
            } catch (err) {
                showMessage('error', err.message || 'Terjadi kesalahan saat menyimpan data model.');
            }
        });

        clearBtn.addEventListener('click', function() {
            clearForm();
            clearMessage();
        });

        tableBody.addEventListener('click', async function(event) {
            const editButton = event.target.closest('[data-edit-id]');
            if (editButton) {
                document.getElementById('model_id').value = editButton.getAttribute('data-edit-id') || '';
                document.getElementById('nama').value = editButton.getAttribute('data-edit-nama') || '';
                document.getElementById('panjang').value = editButton.getAttribute('data-edit-panjang') || '';
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }

            const deleteButton = event.target.closest('[data-delete-id]');
            if (!deleteButton) {
                return;
            }

            const modelId = deleteButton.getAttribute('data-delete-id');
            if (!modelId) {
                showMessage('error', 'Model ID tidak valid.');
                return;
            }

            if (!confirm('Yakin ingin menghapus data model ini?')) {
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
                        model_id: Number(modelId)
                    })
                });

                if (!response.ok) {
                    throw new Error((data && data.message) ? data.message : 'Gagal menghapus data model.');
                }

                showMessage('success', (data && data.message) ? data.message : 'Data model berhasil dihapus.');
                await loadModelList();
            } catch (err) {
                showMessage('error', err.message || 'Terjadi kesalahan saat menghapus data model.');
            }
        });

        loadModelList();
    </script>
</body>

</html>