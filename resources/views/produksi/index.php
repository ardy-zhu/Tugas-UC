<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Produksi') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fb; margin: 0; color: #1f2937; }
        .container { max-width: 980px; margin: 40px auto; padding: 20px; }
        nav { background: #0f172a; padding: 14px 20px; border-radius: 10px; }
        nav a { color: white; text-decoration: none; margin-right: 18px; font-weight: 600; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08); margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { border: 1px solid #e5e7eb; padding: 10px; text-align: left; }
        th { background: #e2e8f0; }
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

            <?php if (empty($items)): ?>
                <p>Belum ada data produksi. Hubungkan ke tabel <strong>produksi</strong> di database untuk menampilkan data.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <?php foreach (array_keys($items[0]) as $column): ?>
                                <th><?= htmlspecialchars($column) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <?php foreach ($item as $value): ?>
                                    <td><?= htmlspecialchars((string) $value) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
