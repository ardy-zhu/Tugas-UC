<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Dashboard') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f7fb; color: #1f2937; }
        .container { max-width: 980px; margin: 40px auto; padding: 20px; }
        nav { background: #0f172a; padding: 14px 20px; border-radius: 10px; }
        nav a { color: #fff; text-decoration: none; margin-right: 18px; font-weight: 600; }
        .card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08); margin-top: 20px; }
        h1 { margin-top: 0; }
        ul { line-height: 1.8; }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <?php foreach ($menu as $item): ?>
                <a href="<?= htmlspecialchars($item['url']) ?>"><?= htmlspecialchars($item['label']) ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="card">
            <h1><?= htmlspecialchars($title) ?></h1>
            <p>Struktur aplikasi ini mengikuti prinsip MVC terbaik: controller untuk request, model untuk akses data, dan view untuk tampilan.</p>

            <ul>
                <li>Controller menangani input dan validasi.</li>
                <li>Model hanya berhubungan dengan database.</li>
                <li>View hanya menampilkan data ke user.</li>
                <li>Business logic dapat dipisahkan ke layer Service jika aplikasi makin kompleks.</li>
            </ul>
        </div>
    </div>
</body>
</html>
