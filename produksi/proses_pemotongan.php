<style>
    .pp-container {
        max-width: 820px;
        font-family: Arial, sans-serif;
    }

    .pp-form-card {
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .pp-form-card h2 {
        margin-top: 0;
    }

    .pp-form-group {
        margin: 12px 0;
    }

    .pp-form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 6px;
    }

    .pp-form-group select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .pp-result-box {
        background: #eaf4ea;
        border: 1px solid #a3d4a3;
        border-radius: 4px;
        padding: 12px;
        margin: 14px 0;
        display: none;
    }

    .pp-result-box.error {
        background: #fde8e8;
        border-color: #e9a3a3;
    }

    .pp-btn-submit {
        background: #1f7a1f;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 22px;
        cursor: pointer;
        font-size: 15px;
    }

    .pp-btn-submit:disabled {
        background: #aaa;
        cursor: not-allowed;
    }

    .pp-btn-add {
        background: #1d4ed8;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 22px;
        cursor: pointer;
        font-size: 15px;
    }

    .pp-btn-add:disabled {
        background: #aaa;
        cursor: not-allowed;
    }

    .pp-btn-clear {
        background: #6c757d;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 22px;
        cursor: pointer;
        font-size: 15px;
    }

    .btn-remove-row {
        background: #b42318;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 13px;
    }

    .btn-delete-history {
        background: #b42318;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 13px;
    }

    .alert {
        padding: 10px;
        margin-bottom: 14px;
        border-radius: 4px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background: #f2f2f2;
    }

    .tag-processed {
        background: #dc3545;
        color: #fff;
        padding: 2px 7px;
        border-radius: 10px;
        font-size: 12px;
    }

    .back-link {
        display: inline-block;
        margin-top: 18px;
        color: #1d4ed8;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }
</style>

<div class="pp-container">
    <h1>Proses Pemotongan</h1>

    <div id="alertBox" class="alert" style="display:none;"></div>

    <div class="pp-form-card">
        <h2>Form Proses Pemotongan</h2>
        <p>Pilih bahan baku dan model. Sistem akan menghitung jumlah item yang dapat dipotong secara otomatis.</p>

        <div class="pp-form-group">
            <label for="bahanSelect">Bahan Baku (belum diproses)</label>
            <select id="bahanSelect">
                <option value="">Memuat data...</option>
            </select>
        </div>

        <div class="pp-form-group">
            <label for="modelSelect">Model</label>
            <select id="modelSelect">
                <option value="">Memuat data...</option>
            </select>
        </div>

        <div id="resultBox" class="pp-result-box">
            <strong>Hasil Perhitungan:</strong>
            <span id="resultText"></span>
        </div>

        <button id="addBtn" class="pp-btn-add" type="button" disabled>+ Tambah ke Daftar</button>
    </div>

    <div id="stagingSection" style="margin-bottom:24px;">
        <h2 style="margin-bottom:8px;">Daftar Pemotongan yang Akan Diproses</h2>
        <table id="stagingTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Bahan Baku</th>
                    <th>Panjang Bahan</th>
                    <th>Model</th>
                    <th>Panjang Model</th>
                    <th>Qty (Item)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="stagingBody"></tbody>
        </table>
        <div style="margin-top:12px;">
            <button id="saveAllBtn" class="pp-btn-submit" type="button">Simpan Semua</button>
            <button id="clearAllBtn" class="pp-btn-clear" type="button" style="margin-left:10px;">Batalkan Semua</button>
        </div>
    </div>

    <h2>Riwayat Proses Pemotongan</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Bahan Baku</th>
                <th>Panjang Bahan</th>
                <th>Model</th>
                <th>Panjang Model</th>
                <th>Qty (Item)</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="historyBody">
            <tr>
                <td colspan="8">Memuat riwayat...</td>
            </tr>
        </tbody>
    </table>

    <a href="index.php?act=pro" class="back-link">&larr; Kembali ke Produksi</a>
</div>

<script>
    (function() {
        const API_URL = 'produksi/proses_pemotongan_api.php';

        const bahanSelect = document.getElementById('bahanSelect');
        const modelSelect = document.getElementById('modelSelect');
        const resultBox = document.getElementById('resultBox');
        const resultText = document.getElementById('resultText');
        const addBtn = document.getElementById('addBtn');
        const saveAllBtn = document.getElementById('saveAllBtn');
        const clearAllBtn = document.getElementById('clearAllBtn');
        const stagingSection = document.getElementById('stagingSection');
        const stagingBody = document.getElementById('stagingBody');
        const alertBox = document.getElementById('alertBox');
        const historyBody = document.getElementById('historyBody');

        let bahanData = [];
        let modelData = [];
        let stagingList = []; // { bahan_id, model_id, nama_bahan, panjang_bahan, nama_model, panjang_model, qty }

        function showAlert(type, msg) {
            alertBox.className = 'alert ' + (type === 'error' ? 'alert-error' : 'alert-success');
            alertBox.textContent = msg;
            alertBox.style.display = 'block';
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function hideAlert() {
            alertBox.style.display = 'none';
        }

        function formatTanggal(unix) {
            if (!unix) return '-';
            const d = new Date(Number(unix) * 1000);
            return d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function getLockedBahanIds() {
            const ids = new Set();
            stagingList.forEach(item => ids.add(String(item.bahan_id)));
            return ids;
        }

        function updateCalculation() {
            const bahanId = bahanSelect.value;
            const modelId = modelSelect.value;

            if (!bahanId || !modelId) {
                resultBox.style.display = 'none';
                addBtn.disabled = true;
                return;
            }

            const bahan = bahanData.find(b => String(b.bahan_id) === bahanId);
            const model = modelData.find(m => String(m.model_id) === modelId);

            if (!bahan || !model) {
                resultBox.style.display = 'none';
                addBtn.disabled = true;
                return;
            }

            const panjangBahan = Number(bahan.panjang_bahan);
            const panjangModel = Number(model.panjang_model);

            if (panjangModel <= 0) {
                resultBox.className = 'pp-result-box error';
                resultText.textContent = ' Panjang model tidak valid.';
                resultBox.style.display = 'block';
                addBtn.disabled = true;
                return;
            }

            const qty = Math.floor(panjangBahan / panjangModel);
            const sisa = (panjangBahan - qty * panjangModel).toFixed(2);

            resultBox.className = 'pp-result-box';
            resultText.innerHTML =
                ' Panjang bahan <strong>' + panjangBahan + '</strong> &divide; ' +
                'Panjang model <strong>' + panjangModel + '</strong> = ' +
                '<strong>' + qty + ' item</strong> (sisa: ' + sisa + ')';
            resultBox.style.display = 'block';
            addBtn.disabled = qty <= 0;
        }

        function renderHistory(rows) {
            if (!rows || rows.length === 0) {
                historyBody.innerHTML = '<tr><td colspan="8">Belum ada riwayat proses pemotongan.</td></tr>';
                return;
            }
            historyBody.innerHTML = rows.map((r, i) =>
                '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td>' + r.nama_bahan + '</td>' +
                '<td>' + r.panjang_bahan + '</td>' +
                '<td>' + r.nama_model + '</td>' +
                '<td>' + r.panjang_model + '</td>' +
                '<td><strong>' + r.qty + '</strong></td>' +
                '<td>' + formatTanggal(r.tanggal) + '</td>' +
                '<td><button class="btn-delete-history" data-produksi-id="' + r.produksi_id + '">Hapus</button></td>' +
                '</tr>'
            ).join('');
        }

        async function deleteHistory(produksiId) {
            const response = await fetch(API_URL, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    produksi_id: Number(produksiId)
                })
            });

            const json = await response.json();
            if (!response.ok || !json.success) {
                throw new Error(json.message || 'Gagal menghapus riwayat proses pemotongan.');
            }

            return json;
        }

        async function loadData() {
            try {
                const res = await fetch(API_URL);
                const json = await res.json();

                if (!res.ok || !json.success) {
                    throw new Error(json.message || 'Gagal memuat data.');
                }

                bahanData = json.data.bahan_baku_tersedia || [];
                modelData = json.data.model || [];

                rebuildBahanOptions();

                modelSelect.innerHTML = modelData.length ?
                    '<option value="">-- Pilih Model --</option>' +
                    modelData.map(m =>
                        '<option value="' + m.model_id + '">' +
                        m.nama + ' (panjang: ' + m.panjang_model + ')' +
                        '</option>'
                    ).join('') :
                    '<option value="">Tidak ada model tersedia</option>';

                renderHistory(json.data.riwayat);
                updateCalculation();
            } catch (err) {
                bahanSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                modelSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                showAlert('error', err.message || 'Terjadi kesalahan saat memuat data.');
            }
        }

        bahanSelect.addEventListener('change', updateCalculation);
        modelSelect.addEventListener('change', updateCalculation);

        function renderStaging() {
            rebuildBahanOptions();
            if (stagingList.length === 0) {
                stagingBody.innerHTML = '<tr><td colspan="7">Belum ada item di daftar.</td></tr>';
                updateCalculation();
                return;
            }
            stagingBody.innerHTML = stagingList.map((item, idx) =>
                '<tr>' +
                '<td>' + (idx + 1) + '</td>' +
                '<td>' + item.nama_bahan + '</td>' +
                '<td>' + item.panjang_bahan + '</td>' +
                '<td>' + item.nama_model + '</td>' +
                '<td>' + item.panjang_model + '</td>' +
                '<td><strong>' + item.qty + '</strong></td>' +
                '<td><button class="btn-remove-row" data-idx="' + idx + '">Hapus</button></td>' +
                '</tr>'
            ).join('');
            updateCalculation();
        }

        function rebuildBahanOptions() {
            const currentValue = String(bahanSelect.value || '');
            const lockedIds = getLockedBahanIds();
            const availableBahan = bahanData.filter(b => !lockedIds.has(String(b.bahan_id)));

            bahanSelect.innerHTML = availableBahan.length ?
                '<option value="">-- Pilih Bahan Baku --</option>' +
                availableBahan.map(b => {
                    return '<option value="' + b.bahan_id + '">' +
                        b.nama + ' (panjang: ' + b.panjang_bahan + ')' +
                        '</option>';
                }).join('') :
                '<option value="">Tidak ada bahan baku tersedia</option>';

            if (currentValue && availableBahan.some(b => String(b.bahan_id) === currentValue)) {
                bahanSelect.value = currentValue;
            } else {
                bahanSelect.value = '';
            }
        }

        stagingBody.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-remove-row');
            if (!btn) return;
            const idx = Number(btn.getAttribute('data-idx'));
            stagingList.splice(idx, 1);
            renderStaging();
        });

        historyBody.addEventListener('click', async function(e) {
            const btn = e.target.closest('.btn-delete-history');
            if (!btn) return;

            const produksiId = Number(btn.getAttribute('data-produksi-id'));
            if (!produksiId) return;

            const confirmed = window.confirm('Hapus riwayat proses pemotongan ini?');
            if (!confirmed) return;

            try {
                hideAlert();
                await deleteHistory(produksiId);
                showAlert('success', 'Riwayat proses pemotongan berhasil dihapus.');
                await loadData();
            } catch (err) {
                showAlert('error', err.message || 'Gagal menghapus riwayat proses pemotongan.');
            }
        });

        clearAllBtn.addEventListener('click', function() {
            stagingList = [];
            renderStaging();
        });

        addBtn.addEventListener('click', function() {
            const bahanId = bahanSelect.value;
            const modelId = modelSelect.value;
            if (!bahanId || !modelId) {
                showAlert('error', 'Bahan baku dan model wajib dipilih.');
                return;
            }
            const bahan = bahanData.find(b => String(b.bahan_id) === bahanId);
            const model = modelData.find(m => String(m.model_id) === modelId);
            if (!bahan || !model) return;

            if (getLockedBahanIds().has(String(bahan.bahan_id))) {
                showAlert('error', 'Bahan baku ini sudah dipilih. Pilih bahan baku lain.');
                return;
            }

            // Cegah kombinasi bahan+model yang sama ditambahkan dua kali
            const duplicate = stagingList.some(
                s => String(s.bahan_id) === String(bahan.bahan_id) && String(s.model_id) === String(model.model_id)
            );
            if (duplicate) {
                showAlert('error', 'Kombinasi bahan baku dan model ini sudah ada di daftar.');
                return;
            }
            const panjangBahan = Number(bahan.panjang_bahan);
            const panjangModel = Number(model.panjang_model);
            const qty = Math.floor(panjangBahan / panjangModel);
            stagingList.push({
                bahan_id: bahan.bahan_id,
                model_id: model.model_id,
                nama_bahan: bahan.nama,
                panjang_bahan: panjangBahan,
                nama_model: model.nama,
                panjang_model: panjangModel,
                qty: qty
            });
            hideAlert();
            renderStaging();
            bahanSelect.value = '';
            modelSelect.value = '';
            resultBox.style.display = 'none';
            addBtn.disabled = true;
        });

        saveAllBtn.addEventListener('click', async function() {
            if (stagingList.length === 0) return;
            hideAlert();
            saveAllBtn.disabled = true;
            saveAllBtn.textContent = 'Menyimpan...';
            const errors = [];
            for (const item of stagingList) {
                try {
                    const res = await fetch(API_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            bahan_id: Number(item.bahan_id),
                            model_id: Number(item.model_id)
                        })
                    });
                    const json = await res.json();
                    if (!res.ok || !json.success) {
                        errors.push(item.nama_bahan + ': ' + (json.message || 'Gagal.'));
                    }
                } catch (err) {
                    errors.push(item.nama_bahan + ': ' + (err.message || 'Terjadi kesalahan.'));
                }
            }
            stagingList = [];
            renderStaging();
            if (errors.length > 0) {
                showAlert('error', 'Beberapa item gagal disimpan: ' + errors.join('; '));
            } else {
                showAlert('success', 'Semua proses pemotongan berhasil disimpan.');
            }
            saveAllBtn.disabled = false;
            saveAllBtn.textContent = 'Simpan Semua';
            await loadData();
        });

        loadData();
    })();
</script>