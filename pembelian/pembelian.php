<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <h1 class="judul-pembelian">Menu Pembelian</h1>
    <div class="pembelian-dropdown">
        <label for="jenis-pembelian">Pilih menu pembelian:</label>
        <select id="jenis-pembelian" onchange="if (this.value) window.location.href = this.value;">
            <option value="">-- Pilih menu --</option>
            <option value="index.php?act=pb2">Bahan Baku</option>
            <option value="index.php?act=pb3">Non Bahan Baku</option>
            <option value="pembelian/returBahan.php">Retur Bahan Baku</option>
            <option value="pembelian/returProduksi.php">Retur Non Bahan Baku</option>
            <option value="pembelian/daftarHutang.php">Daftar Hutang</option>
        </select>
    </div>
</body>

</html>