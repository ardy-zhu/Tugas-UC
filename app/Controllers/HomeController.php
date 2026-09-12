<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'title' => 'Dashboard Manufactur',
            'menu' => [
                ['label' => 'Home', 'url' => '/?page=home'],
                ['label' => 'Pembelian', 'url' => '/?page=pembelian'],
                ['label' => 'Produksi', 'url' => '/?page=produksi'],
            ],
        ]);
    }
}
