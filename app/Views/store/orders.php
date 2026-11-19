<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-3xl font-bold text-[#2C1810] mb-8">Riwayat Pesanan Saya</h1>

    <?php if (empty($invoices)): ?>
        <div class="bg-white rounded-3xl border border-[#E8DFD5] p-12 text-center">
            <p class="font-serif text-xl font-bold text-[#2C1810]">Belum ada pesanan</p>
            <a href="<?= base_url('/') ?>" class="mt-5 inline-flex px-6 py-3 rounded-full bg-[#C86D2D] text-white text-sm font-semibold">
                Pesan Kopi Sekarang
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($invoices as $inv): ?>
                <div class="bg-white rounded-2xl border border-[#E8DFD5] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="text-xs font-bold text-[#C86D2D]"><?= esc($inv['order_id']) ?></div>
                        <div class="font-serif text-lg font-bold text-[#2C1810] mt-0.5">Rp <?= number_format((int) $inv['total_bayar'], 0, ',', '.') ?></div>
                        <div class="text-xs text-[#7A6655] mt-1"><?= esc($inv['tgl_pesan']) ?> &bull; Metode: <span class="uppercase font-semibold"><?= $inv['metode_pembayaran'] === 'midtrans' ? 'Pembayaran Online' : esc($inv['metode_pembayaran']) ?></span></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3.5 py-1 rounded-full text-xs font-bold <?= $inv['status_pembayaran'] === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-800' ?>">
                            <?= strtoupper(esc($inv['status_pembayaran'])) ?>
                        </span>
                        <a href="<?= base_url('checkout/bayar/' . $inv['order_id']) ?>" class="px-4 py-2 rounded-full bg-[#2C1810] text-white text-xs font-semibold hover:bg-[#C86D2D] transition-colors">
                            Detail / Bayar
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
