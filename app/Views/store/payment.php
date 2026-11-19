<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl border border-[#E8DFD5] p-6 sm:p-10 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[#F3EDE4]">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#C86D2D]">Invoice Pesanan</span>
                <h1 class="font-serif text-xl sm:text-3xl font-bold text-[#2C1810] mt-1 break-all sm:break-normal"><?= esc($invoice['order_id']) ?></h1>
                <p class="text-xs text-[#7A6655] mt-1">Tanggal: <?= esc($invoice['tgl_pesan']) ?> &bull; Batas Bayar: <?= esc($invoice['batas_bayar']) ?></p>
            </div>
            <div>
                <?php
                    $status = $invoice['status_pembayaran'] ?? 'pending';
                    $badge  = match ($status) {
                        'paid'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'failed', 'expired', 'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                        default     => 'bg-amber-50 text-amber-800 border-amber-200',
                    };
                    $label = match ($status) {
                        'paid'      => 'Lunas',
                        'expired'   => 'Kedaluwarsa',
                        'failed'    => 'Gagal',
                        'cancelled' => 'Dibatalkan',
                        default     => 'Menunggu Pembayaran',
                    };
                ?>
                <span class="inline-flex px-4 py-1.5 rounded-full border text-xs font-bold <?= $badge ?>">
                    <?= $label ?>
                </span>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-6 border-b border-[#F3EDE4] text-sm">
            <div>
                <div class="text-xs text-[#9C8B7A] font-semibold uppercase">Penerima</div>
                <div class="font-bold text-[#2C1810] mt-1"><?= esc($invoice['nama']) ?></div>
                <div class="text-[#5C4738]"><?= esc($invoice['no_telp'] ?? '-') ?></div>
            </div>
            <div>
                <div class="text-xs text-[#9C8B7A] font-semibold uppercase">Alamat Pengiriman</div>
                <div class="text-[#5C4738] mt-1"><?= esc($invoice['alamat'] ?? '-') ?></div>
                <div class="text-xs text-[#7A6655] mt-1">Metode: <strong class="uppercase"><?= $invoice['metode_pembayaran'] === 'midtrans' ? 'Pembayaran Online' : esc($invoice['metode_pembayaran']) ?></strong></div>
            </div>
        </div>

        <!-- Items -->
        <div class="py-6">
            <h2 class="font-serif text-lg font-bold text-[#2C1810] mb-4">Detail Produk</h2>
            <div class="divide-y divide-[#F3EDE4]">
                <?php foreach ($pesanan as $psn): ?>
                    <div class="py-3 flex justify-between items-center text-sm">
                        <div>
                            <div class="font-semibold text-[#2C1810]"><?= esc($psn['nama_kopi']) ?></div>
                            <div class="text-xs text-[#7A6655]"><?= (int) $psn['jumlah'] ?>x &bull; Rp <?= number_format((int) $psn['harga'], 0, ',', '.') ?> (<?= esc($psn['pilihan'] ?: 'Reguler') ?>)</div>
                        </div>
                        <div class="font-bold text-[#2C1810]">
                            Rp <?= number_format((int) $psn['jumlah'] * (int) $psn['harga'], 0, ',', '.') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4 pt-4 border-t border-[#E8DFD5] flex justify-between items-center">
                <span class="font-semibold text-[#4A2C20]">Grand Total</span>
                <span class="text-2xl font-bold text-[#C86D2D]">Rp <?= number_format((int) $invoice['total_bayar'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Payment Action -->
        <?php if ($status === 'pending' && $invoice['metode_pembayaran'] === 'midtrans'): ?>
            <div class="pt-4 border-t border-[#F3EDE4] space-y-4">
                <?php if ($midtransConfigured && ! empty($invoice['snap_token'])): ?>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button id="pay-button" type="button"
                                class="btn-smooth flex-1 py-4 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] text-white font-semibold text-sm shadow-md cursor-pointer">
                            Bayar Sekarang
                        </button>
                        <a href="<?= base_url('checkout/finish') ?>?order_id=<?= urlencode($invoice['order_id']) ?>"
                           class="btn-smooth px-6 py-4 rounded-full border border-[#E8DFD5] text-[#4A2C20] hover:border-[#2C1810] text-sm font-semibold text-center">
                            Cek Status Pembayaran
                        </a>
                    </div>
                <?php else: ?>
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed">
                        <strong>Mode Simulasi Pembayaran:</strong> Gateway pembayaran online belum dikonfigurasi pada <code>.env</code>. Gunakan tombol simulasi di bawah untuk menguji status lunas.
                    </div>
                    <?php if (ENVIRONMENT === 'development'): ?>
                        <form action="<?= base_url('checkout/simulasi/' . $invoice['order_id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="w-full py-3.5 rounded-full bg-[#2C1810] hover:bg-[#4A2C20] text-white font-semibold text-sm transition-colors cursor-pointer">
                                Simulasi Pembayaran Berhasil (Sandbox Demo)
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php elseif ($invoice['metode_pembayaran'] === 'cod' && $status === 'pending'): ?>
            <div class="p-4 rounded-2xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm text-[#4A2C20]">
                Pesanan Anda menggunakan metode <strong>COD (Bayar di Tempat)</strong>. Silakan siapkan uang pas sebesar <strong>Rp <?= number_format((int) $invoice['total_bayar'], 0, ',', '.') ?></strong>.
            </div>
        <?php endif; ?>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="<?= base_url('pesanan') ?>" class="px-5 py-2.5 rounded-full border border-[#E8DFD5] text-sm font-semibold text-[#4A2C20] hover:border-[#2C1810]">
                Riwayat Pesanan
            </a>
            <a href="<?= base_url('/') ?>" class="px-5 py-2.5 rounded-full bg-[#FAF7F2] text-sm font-semibold text-[#2C1810] hover:bg-[#E8DFD5]">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if ($midtransConfigured && ! empty($invoice['snap_token'])): ?>
<script src="<?= esc($snapJsUrl) ?>" data-client-key="<?= esc($midtransClientKey) ?>"></script>
<script>
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function () {
            window.snap.pay('<?= esc($invoice['snap_token']) ?>', {
                onSuccess: function () {
                    window.location.href = '<?= base_url('checkout/finish') ?>?order_id=<?= urlencode($invoice['order_id']) ?>';
                },
                onPending: function () {
                    window.location.href = '<?= base_url('checkout/finish') ?>?order_id=<?= urlencode($invoice['order_id']) ?>';
                },
                onError: function () {
                    window.location.href = '<?= base_url('checkout/finish') ?>?order_id=<?= urlencode($invoice['order_id']) ?>';
                }
            });
        });
    }
</script>
<?php endif; ?>
<?= $this->endSection() ?>
