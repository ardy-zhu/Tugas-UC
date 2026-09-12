<?php

namespace App\Controllers;

use App\Core\Controller;

final class LegacyController extends Controller
{
    public function data(): void
    {
        $this->legacy('data/data.php');
    }

    public function model(): void
    {
        $this->legacy('data/model.php');
    }

    public function pembelian(): void
    {
        $this->legacy('pembelian/pembelian.php');
    }

    public function gudang(): void
    {
        $this->legacy('gudang/gudang.php');
    }

    public function produksi(): void
    {
        $this->legacy('produksi/produksi.php');
    }

    public function pemotongan(): void
    {
        $this->legacy('produksi/pemotongan.php');
    }

    public function returProduksi(): void
    {
        $this->legacy('produksi/retur_produksi.php');
    }

    public function penerimaanHasilKerja(): void
    {
        $this->legacy('produksi/penerimaan_hasil_kerja.php');
    }

    public function suratPerintahKerja(): void
    {
        $this->legacy('produksi/surat_perintah_kerja.php');
    }

    public function prosesPemotongan(): void
    {
        $this->legacy('produksi/proses_pemotongan.php');
    }

    public function pembelianBahanBaku(): void
    {
        $this->legacy('pembelian/pembelianBahanBaku.php');
    }

    public function penjualan(): void
    {
        $this->legacy('penjualan/penjualan.php');
    }

    public function laporan(): void
    {
        $this->legacy('laporan/laporan.php');
    }

    public function laporanDetail(): void
    {
        $this->legacy('laporan/detail.php');
    }

    public function lainLain(): void
    {
        $this->legacy('lainlain/lainLain.php');
    }

    private function legacy(string $relativePath): void
    {
        $file = APP_ROOT . '/' . $relativePath;

        if (!is_file($file)) {
            http_response_code(404);
            echo '<h2>Halaman tidak ditemukan</h2>';
            return;
        }

        require $file;
    }
}
