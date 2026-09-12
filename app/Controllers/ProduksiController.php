<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProduksiModel;

class ProduksiController extends Controller
{
    public function index(): void
    {
        $model = new ProduksiModel();

        $this->view('produksi/index', [
            'title' => 'Data Produksi',
            'items' => $model->getAll(),
            'menu' => [
                ['label' => 'Home', 'url' => '/?page=home'],
                ['label' => 'Pembelian', 'url' => '/?page=pembelian'],
                ['label' => 'Produksi', 'url' => '/?page=produksi'],
            ],
        ]);
    }
}
