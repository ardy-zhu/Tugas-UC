<?php

use App\Controllers\HomeController;
use App\Controllers\PembelianController;
use App\Controllers\ProduksiController;

return [
    '/' => [HomeController::class, 'index'],
    '/home' => [HomeController::class, 'index'],
    '/pembelian' => [PembelianController::class, 'index'],
    '/produksi' => [ProduksiController::class, 'index'],
];
