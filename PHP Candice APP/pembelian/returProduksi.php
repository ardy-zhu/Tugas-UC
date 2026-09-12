<?php
// Halaman utama modul penjualan.
$menu = [
	['icon' => '🛒', 'title' => 'Penjualan', 'description' => 'Kelola transaksi penjualan pelanggan.', 'href' => 'penjualan.php'],
	['icon' => '↩️', 'title' => 'Retur', 'description' => 'Catat dan pantau retur penjualan.', 'href' => 'retur.php'],
	['icon' => '💳', 'title' => 'Daftar Piutang Pelanggan', 'description' => 'Lihat piutang dan jatuh tempo pelanggan.', 'href' => 'piutangPelanggan.php'],
	['icon' => '🧾', 'title' => 'Detail Penerimaan', 'description' => 'Catat penerimaan pembayaran pelanggan.', 'href' => 'detailPenerimaan.php'],
	['icon' => '💸', 'title' => 'Pencatatan Pengeluaran', 'description' => 'Catat biaya operasional dan pengeluaran.', 'href' => 'pengeluaran.php'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Penjualan | Candice APP</title>
	<style>
		:root { --primary:#2563eb; --dark:#172554; --muted:#64748b; --bg:#f8fafc; }
		* { box-sizing:border-box; }
		body { margin:0; font-family:Arial, sans-serif; color:#1e293b; background:var(--bg); }
		.layout { display:flex; min-height:100vh; }
		aside { width:245px; padding:28px 18px; color:#fff; background:var(--dark); }
		.brand { margin:0 12px 38px; font-size:21px; font-weight:700; }
		.nav-title { margin:0 12px 12px; color:#93c5fd; font-size:11px; text-transform:uppercase; letter-spacing:1px; }
		nav a { display:block; padding:12px; margin:5px 0; border-radius:8px; color:#dbeafe; text-decoration:none; }
		nav a.active, nav a:hover { background:#1d4ed8; color:#fff; }
		main { width:100%; padding:38px 5%; }
		.heading { display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; }
		h1 { margin:0 0 8px; font-size:28px; color:var(--dark); }
		.subtitle { margin:0; color:var(--muted); }
		.date { color:var(--muted); font-size:14px; }
		.cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; }
		.card { display:block; padding:24px; border:1px solid #e2e8f0; border-radius:12px; color:inherit; text-decoration:none; background:#fff; box-shadow:0 3px 12px #0f172a0b; transition:.2s; }
		.card:hover { border-color:#93c5fd; transform:translateY(-3px); box-shadow:0 8px 20px #2563eb1a; }
		.icon { display:grid; place-items:center; width:48px; height:48px; margin-bottom:18px; border-radius:10px; background:#dbeafe; font-size:24px; }
		.card h2 { margin:0 0 9px; font-size:18px; color:var(--dark); }
		.card p { margin:0; color:var(--muted); font-size:14px; line-height:1.5; }
		@media (max-width:700px) { .layout { display:block; } aside { width:100%; padding:20px; } .brand { margin-bottom:20px; } nav { display:flex; flex-wrap:wrap; gap:4px; } nav a { margin:0; } main { padding:28px 20px; } .heading { display:block; } .date { display:block; margin-top:12px; } }
	</style>
</head>
<body>
	<div class="layout">
		<aside>
			<div class="brand">Candice APP</div>
			<div class="nav-title">Menu Utama</div>
			<nav>
				<a href="../index.php">Dashboard</a>
				<a href="returProduksi.php" class="active">Penjualan</a>
				<a href="../pembelian/index.php">Pembelian</a>
			</nav>
		</aside>
		<main>
			<div class="heading">
				<div><h1>Penjualan</h1><p class="subtitle">Kelola seluruh aktivitas penjualan dan keuangan pelanggan.</p></div>
				<div class="date"><?= date('d F Y') ?></div>
			</div>
			<section class="cards" aria-label="Menu penjualan">
				<?php foreach ($menu as $item): ?>
					<a class="card" href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>">
						<div class="icon"><?= $item['icon'] ?></div>
						<h2><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h2>
						<p><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?></p>
					</a>
				<?php endforeach; ?>
			</section>
		</main>
	</div>
</body>
</html>
