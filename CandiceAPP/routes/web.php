<?php

use App\Controllers\HomeController;
use App\Controllers\PembelianController;
use App\Controllers\ProduksiController;
use App\Controllers\LegacyController;

return [
    '/' => [HomeController::class, 'index'],
    '/home' => [HomeController::class, 'index'],
    '/pembelian' => [PembelianController::class, 'index'],
    '/produksi' => [ProduksiController::class, 'index'],
    '/data' => [LegacyController::class, 'data'],
    '/model' => [LegacyController::class, 'model'],
    '/pembelian' => [LegacyController::class, 'pembelian'],
    '/gudang' => [LegacyController::class, 'gudang'],
    '/produksi-lama' => [LegacyController::class, 'produksi'],
    '/pemotongan' => [LegacyController::class, 'pemotongan'],
    '/retur-produksi' => [LegacyController::class, 'returProduksi'],
    '/penerimaan-hasil-kerja' => [LegacyController::class, 'penerimaanHasilKerja'],
    '/surat-perintah-kerja' => [LegacyController::class, 'suratPerintahKerja'],
    '/proses-pemotongan' => [LegacyController::class, 'prosesPemotongan'],
    '/pembelian-bahan-baku' => [LegacyController::class, 'pembelianBahanBaku'],
    '/penjualan' => [LegacyController::class, 'penjualan'],
    '/laporan' => [LegacyController::class, 'laporan'],
    '/laporan-detail' => [LegacyController::class, 'laporanDetail'],
    '/lain-lain' => [LegacyController::class, 'lainLain'],
];
