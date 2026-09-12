<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PembelianModel;

class PembelianController extends Controller
{
    public function index(): void
    {
        $model = new PembelianModel();

        $this->view('pembelian/index', [
            'title' => 'Data Pembelian',
            'items' => $model->getAll(),
            'menu' => [
                ['label' => 'Home', 'url' => '/?page=home'],
                ['label' => 'Pembelian', 'url' => '/?page=pembelian'],
                ['label' => 'Produksi', 'url' => '/?page=produksi'],
            ],
        ]);
    }
}
