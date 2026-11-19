<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-3xl font-bold text-[#2C1810] mb-8">Keranjang Belanja</h1>

    <?php if (empty($cart)): ?>
        <div class="bg-white rounded-3xl border border-[#E8DFD5] p-12 text-center">
            <p class="font-serif text-xl font-bold text-[#2C1810]">Keranjang Anda masih kosong</p>
            <p class="mt-2 text-sm text-[#7A6655]">Yuk pilih kopi favorit Anda dari menu roastery kami.</p>
            <a href="<?= base_url('/') ?>" class="mt-6 inline-flex px-6 py-3 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] text-white text-sm font-semibold transition-colors">
                Mulai Belanja
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                <?php foreach ($cart as $item): ?>
                    <div class="bg-white rounded-2xl border border-[#E8DFD5] p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                        <img src="<?= base_url('uploads/' . esc($item['gambar'])) ?>"
                             alt="<?= esc($item['nama_kopi']) ?>"
                             class="w-24 h-20 rounded-xl object-cover bg-[#F3EDE4] shrink-0">

                        <div class="flex-1 min-w-0">
                            <h2 class="font-serif text-lg font-bold text-[#2C1810] truncate"><?= esc($item['nama_kopi']) ?></h2>
                            <p class="text-xs text-[#7A6655] mt-0.5"><?= esc($item['kategori']) ?> &bull; <?= esc($item['pilihan'] ?? 'Reguler') ?></p>
                            <p class="text-sm font-bold text-[#C86D2D] mt-1">
                                Rp <?= number_format((int) $item['harga'], 0, ',', '.') ?>
                            </p>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-2 pt-3 sm:pt-0 border-t sm:border-t-0 border-[#F3EDE4]">
                            <form action="<?= base_url('keranjang/update') ?>" method="post" class="inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="key" value="<?= esc($item['key']) ?>">
                                <input type="hidden" name="action" value="dec">
                                <button type="submit" class="w-8 h-8 rounded-full border border-[#E8DFD5] hover:border-[#2C1810] text-sm font-bold cursor-pointer">-</button>
                            </form>

                            <span class="w-8 text-center text-sm font-bold"><?= (int) $item['qty'] ?></span>

                            <form action="<?= base_url('keranjang/update') ?>" method="post" class="inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="key" value="<?= esc($item['key']) ?>">
                                <input type="hidden" name="action" value="inc">
                                <button type="submit" class="w-8 h-8 rounded-full border border-[#E8DFD5] hover:border-[#2C1810] text-sm font-bold cursor-pointer">+</button>
                            </form>

                            <form action="<?= base_url('keranjang/update') ?>" method="post" class="inline ml-2">
                                <?= csrf_field() ?>
                                <input type="hidden" name="key" value="<?= esc($item['key']) ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800 cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="flex items-center justify-between pt-2">
                    <a href="<?= base_url('/') ?>" class="text-sm font-semibold text-[#4A2C20] hover:text-[#C86D2D]">&larr; Lanjutkan Belanja</a>
                    <form action="<?= base_url('keranjang/hapus') ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800 cursor-pointer">Kosongkan Keranjang</button>
                    </form>
                </div>
            </div>

            <!-- Summary -->
            <div class="bg-white rounded-3xl border border-[#E8DFD5] p-6 space-y-5">
                <h3 class="font-serif text-xl font-bold text-[#2C1810]">Ringkasan Pesanan</h3>
                <div class="space-y-2 text-sm border-b border-[#F3EDE4] pb-4">
                    <div class="flex justify-between text-[#5C4738]">
                        <span>Total Item</span>
                        <span class="font-semibold"><?= array_sum(array_column($cart, 'qty')) ?> cup/pack</span>
                    </div>
                    <div class="flex justify-between text-[#5C4738]">
                        <span>Total Harga</span>
                        <span class="font-bold text-[#2C1810]">Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                </div>
                <a href="<?= base_url('checkout') ?>" class="block w-full text-center py-3.5 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] text-white font-semibold text-sm transition-colors shadow-md">
                    Lanjut ke Checkout
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
