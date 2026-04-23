<h1>Pemotongan</h1>
<p>Hitung estimasi hasil produksi dari panjang bahan baku dan panjang model.</p>

<div class="pemotongan-form">
    <label for="bahanSelect">Pilih Bahan Baku</label>
    <select id="bahanSelect">
        <option value="">Memuat data bahan baku...</option>
    </select>

    <label for="modelSelect">Pilih Model</label>
    <select id="modelSelect">
        <option value="">Memuat data model...</option>
    </select>

    <button type="button" id="hitungProduksiBtn">Hitung Hasil Produksi</button>
</div>

<div id="hasilPemotongan" class="hasil-pemotongan" aria-live="polite"></div>

<a href="index.php?act=pro" class="back-link">Kembali ke Produksi</a>

<script>
    (function() {
        const apiUrl = 'data/hasil_produksi_api.php';
        const bahanSelect = document.getElementById('bahanSelect');
        const modelSelect = document.getElementById('modelSelect');
        const hitungBtn = document.getElementById('hitungProduksiBtn');
        const hasilEl = document.getElementById('hasilPemotongan');

        function formatAngka(value) {
            return new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 4
            }).format(Number(value));
        }

        function setPesan(message, type) {
            hasilEl.className = 'hasil-pemotongan ' + type;
            hasilEl.innerHTML = '<p>' + message + '</p>';
        }

        function renderOptions(selectEl, items, valueKey, labelKey, lengthKey) {
            selectEl.innerHTML = '<option value="">-- Pilih --</option>';
            for (const item of items) {
                const option = document.createElement('option');
                option.value = item[valueKey];
                option.textContent = item[labelKey] + ' (panjang: ' + item[lengthKey] + ')';
                selectEl.appendChild(option);
            }
        }

        async function loadMasterData() {
            try {
                const response = await fetch(apiUrl);
                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Gagal mengambil data master.');
                }

                const bahanBaku = payload.data && payload.data.bahan_baku ? payload.data.bahan_baku : [];
                const model = payload.data && payload.data.model ? payload.data.model : [];

                renderOptions(bahanSelect, bahanBaku, 'bahan_id', 'nama', 'panjang_bahan');
                renderOptions(modelSelect, model, 'model_id', 'nama', 'panjang_model');
                setPesan('Pilih bahan baku dan model, lalu klik tombol hitung.', 'info');
            } catch (error) {
                bahanSelect.innerHTML = '<option value="">Gagal memuat data bahan baku</option>';
                modelSelect.innerHTML = '<option value="">Gagal memuat data model</option>';
                setPesan(error.message, 'error');
            }
        }

        async function hitungHasilProduksi() {
            const bahanId = bahanSelect.value;
            const modelId = modelSelect.value;

            if (!bahanId || !modelId) {
                setPesan('Bahan baku dan model wajib dipilih.', 'error');
                return;
            }

            setPesan('Menghitung hasil produksi...', 'info');

            try {
                const response = await fetch(apiUrl + '?bahan_id=' + encodeURIComponent(bahanId) + '&model_id=' + encodeURIComponent(modelId));
                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Gagal menghitung hasil produksi.');
                }

                const data = payload.data;
                const hasil = data.perhitungan;

                hasilEl.className = 'hasil-pemotongan success';
                hasilEl.innerHTML = '' +
                    '<h3>Hasil Perhitungan</h3>' +
                    '<p><strong>Bahan Baku:</strong> ' + data.bahan_baku.nama + ' (panjang: ' + data.bahan_baku.panjang_bahan + ')</p>' +
                    '<p><strong>Model:</strong> ' + data.model.nama + ' (panjang: ' + data.model.panjang_model + ')</p>' +
                    '<p><strong>Rumus:</strong> ' + hasil.rumus + '</p>' +
                    '<p><strong>Hasil Teoritis:</strong> ' + formatAngka(hasil.hasil_teoritis) + '</p>' +
                    '<p><strong>Jumlah Hasil:</strong> ' + formatAngka(hasil.jumlah_hasil) + '</p>' +
                    '<p><strong>Sisa Panjang:</strong> ' + formatAngka(hasil.sisa_panjang) + '</p>';
            } catch (error) {
                setPesan(error.message, 'error');
            }
        }

        hitungBtn.addEventListener('click', hitungHasilProduksi);
        loadMasterData();
    })();
</script>