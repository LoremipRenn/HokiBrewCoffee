<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Struk Pembayaran') ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #FAF7F2; color: #1f1f1f; margin: 0; padding: 24px; }
        .receipt { max-width: 360px; margin: 0 auto; background: #fff; padding: 24px; border: 1px dashed #ccc; border-radius: 12px; }
        .center { text-align: center; }
        .line { border-top: 1px dashed #999; margin: 12px 0; }
        .row { display: flex; justify-content: space-between; font-size: 13px; margin: 4px 0; gap: 8px; }
        .bold { font-weight: bold; }
        .actions { text-align: center; margin-top: 20px; display: flex; gap: 8px; justify-content: center; }
        .btn { padding: 10px 18px; border-radius: 999px; border: none; font-family: sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; }
        .btn-print { background: #C86D2D; color: #fff; }
        .btn-back { background: #2C1810; color: #fff; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt { border: none; max-width: 100%; padding: 0; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="center">
            <div class="bold" style="font-size: 18px;">HOKIBREWCOFFEE</div>
            <div style="font-size: 12px;">Specialty Coffee &amp; Roastery</div>
            <div style="font-size: 11px; margin-top: 4px;"><?= esc($invoice['tgl_pesan']) ?></div>
        </div>

        <div class="line"></div>

        <div class="row"><span>Order ID</span><span class="bold"><?= esc($invoice['order_id']) ?></span></div>
        <div class="row"><span>Pemesan</span><span><?= esc($invoice['nama']) ?></span></div>
        <div class="row"><span>Metode</span><span style="text-transform: uppercase;"><?= $invoice['metode_pembayaran'] === 'midtrans' ? 'Pembayaran Online' : esc($invoice['metode_pembayaran']) ?></span></div>
        <div class="row"><span>Status</span><span class="bold" style="text-transform: uppercase;"><?= esc($invoice['status_pembayaran']) ?></span></div>

        <div class="line"></div>

        <?php $total = 0; foreach ($pesanan as $psn): $sub = (int) $psn['jumlah'] * (int) $psn['harga']; $total += $sub; ?>
            <div style="margin-bottom: 6px;">
                <div class="bold" style="font-size: 13px;"><?= esc($psn['nama_kopi']) ?></div>
                <div class="row">
                    <span><?= (int) $psn['jumlah'] ?> x Rp <?= number_format((int) $psn['harga'], 0, ',', '.') ?></span>
                    <span>Rp <?= number_format($sub, 0, ',', '.') ?></span>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="line"></div>

        <div class="row bold" style="font-size: 15px;">
            <span>TOTAL</span>
            <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
        </div>

        <div class="line"></div>
        <div class="center" style="font-size: 11px;">
            Terima kasih telah menyeduh bersama HokiBrewCoffee!
        </div>
    </div>

    <div class="actions">
        <button onclick="window.print()" class="btn btn-print">Cetak / Print Struk</button>
        <a href="<?= base_url('admin/invoice/' . $invoice['id']) ?>" class="btn btn-back">Kembali</a>
    </div>
</body>
</html>
