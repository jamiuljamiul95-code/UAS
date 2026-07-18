<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #111827;
            font-size: 12px;
        }

        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0 0 4px;
            color: #2563eb;
        }

        .header p {
            margin: 0;
            color: #6b7280;
            font-size: 11px;
        }

        .summary {
            width: 100%;
            margin-bottom: 24px;
        }

        .summary td {
            width: 33.33%;
            padding: 12px;
            border: 1px solid #e5e7eb;
        }

        .summary .label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .summary .value {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            margin-top: 4px;
        }

        h2 {
            font-size: 14px;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 6px;
            margin-top: 28px;
            margin-bottom: 10px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            background: #f3f4f6;
            text-align: left;
            padding: 8px 10px;
            font-size: 10.5px;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }

        table.data td {
            padding: 8px 10px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 11.5px;
        }

        .text-right {
            text-align: right;
        }

        .footer-note {
            margin-top: 30px;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Laporan Penjualan — Mizu Design</h1>
        <p>Periode: <?= htmlspecialchars($rangeLabel) ?> &nbsp;|&nbsp; Dicetak: <?= date('d M Y, H:i') ?></p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Total Pendapatan (Periode Ini)</div>
                <div class="value">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></div>
            </td>
            <td>
                <div class="label">Jumlah Order Terbayar</div>
                <div class="value"><?= (int) $totalOrdersInRange ?></div>
            </td>
            <td>
                <div class="label">Rata-rata per Order</div>
                <div class="value">
                    Rp
                    <?= $totalOrdersInRange > 0 ? number_format($totalRevenue / $totalOrdersInRange, 0, ',', '.') : '0' ?>
                </div>
            </td>
        </tr>
    </table>

    <h2>Rincian Penjualan</h2>
    <?php if (empty($salesData)): ?>
        <p style="color:#9ca3af">Tidak ada data penjualan pada periode ini.</p>
    <?php else: ?>
        <table class="data">
            <thead>
                <tr>
                    <th><?= $range === 'yearly' ? 'Tahun' : ($range === 'monthly' ? 'Bulan' : 'Tanggal') ?></th>
                    <th class="text-right">Jumlah Order</th>
                    <th class="text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salesData as $row): ?>
                    <?php
                    if ($range === 'daily') {
                        $label = date('d M Y', strtotime($row['date']));
                    } elseif ($range === 'monthly') {
                        $label = date('F Y', strtotime($row['period'] . '-01'));
                    } else {
                        $label = $row['period'];
                    }
                    ?>
                    <tr>
                        <td><?= $label ?></td>
                        <td class="text-right"><?= (int) $row['order_count'] ?></td>
                        <td class="text-right">Rp <?= number_format($row['revenue'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2>Produk Terlaris</h2>
    <?php if (empty($topProducts)): ?>
        <p style="color:#9ca3af">Belum ada penjualan produk yang tercatat.</p>
    <?php else: ?>
        <table class="data">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-right">Jumlah Terjual</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topProducts as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td class="text-right"><?= (int) $p['sold_count'] ?>x</td>
                        <td class="text-right">Rp <?= number_format($p['revenue'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2>Detail Transaksi & Bukti Pembayaran</h2>
    <?php if (empty($transactions)): ?>
        <p style="color:#9ca3af">Tidak ada transaksi pada periode ini.</p>
    <?php else: ?>
        <table class="data">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pembeli</th>
                    <th class="text-right">Total</th>
                    <th>Tanggal</th>
                    <th>Bukti Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['invoice']) ?></td>
                        <td><?= htmlspecialchars($t['customer_name']) ?></td>
                        <td class="text-right">Rp <?= number_format($t['total'], 0, ',', '.') ?></td>
                        <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>
                        <td><?= !empty($t['payment_proof']) ? '✔ Ada' : '— Tidak ada' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="footer-note">
        Laporan ini dibuat otomatis oleh sistem admin Mizu Design pada <?= date('d M Y, H:i') ?> WIB.
    </div>

</body>

</html>