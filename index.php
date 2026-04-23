<!DOCTYPE html>
<html>

<head>
    <title>Contoh Mini Project</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="header">
        <h1>Manufacture Integration APP</h1>
    </div>
    <div class="navigation">
        <?php include "navigasi.php"; ?>
    </div>
    <div class="main">
        <?php
        if (!isset($_GET["act"])) {
            include_once "beranda.php";
        } elseif ($_GET["act"] == "data") {
            include_once "data\data.php";
        } elseif ($_GET["act"] == "model") {
            include_once "data\model.php";
        } elseif ($_GET["act"] == "pb") {
            include_once "pembelian\pembelian.php";
        } elseif ($_GET["act"] == "gd") {
            include_once "Gudang.php";
        } elseif ($_GET["act"] == "pro") {
            include_once "produksi\produksi.php";
        } elseif ($_GET["act"] == "pro_pemotongan") {
            include_once "produksi\pemotongan.php";
        } elseif ($_GET["act"] == "pro_retur") {
            include_once "produksi\retur_produksi.php";
        } elseif ($_GET["act"] == "pro_penerimaan") {
            include_once "produksi\penerimaan_hasil_kerja.php";
        } elseif ($_GET["act"] == "pro_spk") {
            include_once "produksi\surat_perintah_kerja.php";
        } elseif ($_GET["act"] == "pro_proses_pemotongan") {
            include_once "produksi\proses_pemotongan.php";
        } elseif ($_GET["act"] == "pb2") {
            include_once "pembelian\pembelianBahanBaku.php";
        } elseif ($_GET["act"] == "pen") {
            include_once "Penjualan.php";
        } elseif ($_GET["act"] == "lap") {
            include_once "Laporan.php";
        } elseif ($_GET["act"] == "lain") {
            include_once "LainLain.php";
        } else {
            echo "<h2>Halaman tidak ditemukan</h2>";
        }

        ?>
    </div>
    <div class="footer">
        &copy; 2024 - Mini Project Web Programming
    </div>
</body>

</html>