<?php
$reports = [
    "lap1" => [
        "title" => "Laporan Pembelian",
        "description" => "Gunakan menu Pembelian untuk memasukkan dan meninjau transaksi pembelian bahan baku maupun non-bahan baku.",
        "link" => "index.php?act=pb",
        "linkText" => "Buka Menu Pembelian",
    ],
    "lap2" => [
        "title" => "Laporan Penjualan",
        "description" => "Gunakan menu Penjualan untuk melihat dan mengelola transaksi penjualan, retur, serta piutang pelanggan.",
        "link" => "index.php?act=pen",
        "linkText" => "Buka Menu Penjualan",
    ],
    "lap3" => [
        "title" => "Laporan Produksi",
        "description" => "Gunakan menu Produksi untuk meninjau pemotongan, surat perintah kerja, penerimaan hasil, dan retur produksi.",
        "link" => "index.php?act=pro",
        "linkText" => "Buka Menu Produksi",
    ],
    "lap4" => [
        "title" => "Laporan Gudang",
        "description" => "Gunakan menu Gudang untuk memeriksa stok, ketersediaan inventory, pengiriman, dan penerimaan barang.",
        "link" => "index.php?act=gd",
        "linkText" => "Buka Menu Gudang",
    ],
];

$report = $reports[$_GET["act"] ?? ""] ?? null;
?>

<section class="report-detail">
    <?php if ($report): ?>
        <h1><?php echo htmlspecialchars($report["title"], ENT_QUOTES, "UTF-8"); ?></h1>
        <p><?php echo htmlspecialchars($report["description"], ENT_QUOTES, "UTF-8"); ?></p>
        <a class="action-link" href="<?php echo htmlspecialchars($report["link"], ENT_QUOTES, "UTF-8"); ?>">
            <?php echo htmlspecialchars($report["linkText"], ENT_QUOTES, "UTF-8"); ?>
        </a>
        <a class="back-link" href="index.php?act=lap">Kembali ke Laporan</a>
    <?php else: ?>
        <h1>Laporan Tidak Ditemukan</h1>
        <p>Jenis laporan yang dipilih tidak tersedia.</p>
        <a class="back-link" href="index.php?act=lap">Kembali ke Laporan</a>
    <?php endif; ?>
</section>
