<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="auth-stagger auth-delay-1 mb-8">
        <a href="<?= base_url('keranjang') ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#7A6655] hover:text-[#C86D2D] transition-colors">
            &larr; Kembali ke Keranjang
        </a>
        <h1 class="font-serif text-3xl font-bold text-[#2C1810] mt-2">Checkout &amp; Pengiriman</h1>
        <p class="text-sm text-[#7A6655] mt-1">Lengkapi alamat pengiriman dan pilih metode pembayaran yang paling nyaman.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Checkout Form -->
        <form action="<?= base_url('checkout/proses') ?>" method="post" class="auth-stagger auth-delay-2 lg:col-span-2 bg-white rounded-3xl border border-[#E8DFD5] p-6 sm:p-8 space-y-6 shadow-xs">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-2">Nama Penerima</label>
                    <input type="text" name="nama" value="<?= esc(old('nama', session()->get('nama'))) ?>" required
                           class="w-full px-4 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-2">No. WhatsApp / Telepon (Opsional)</label>
                    <input type="text" name="no_telp" value="<?= esc(old('no_telp')) ?>" placeholder="0812xxxxxxxx"
                           class="w-full px-4 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-2">Alamat Pengiriman Lengkap</label>
                <textarea name="alamat" rows="3" required placeholder="Jalan, nomor rumah, kecamatan, kota..."
                          class="w-full px-4 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all"><?= esc(old('alamat')) ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-2">Catatan Pesanan (Opsional)</label>
                <input type="text" name="catatan" value="<?= esc(old('catatan')) ?>" placeholder="Contoh: Kurangi gula, kirim sore"
                       class="w-full px-4 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-3">Metode Pembayaran</label>
                <?php $selectedMethod = old('metode_pembayaran', 'midtrans'); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="paymentMethodGroup">
                    <label data-method="midtrans"
                           class="payment-card flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200 <?= $selectedMethod === 'midtrans' ? 'border-[#C86D2D] bg-[#FAF7F2] shadow-sm' : 'border-[#E8DFD5] bg-white hover:border-[#C86D2D]/50' ?>">
                        <input type="radio" name="metode_pembayaran" value="midtrans" <?= $selectedMethod === 'midtrans' ? 'checked' : '' ?> class="mt-1 accent-[#C86D2D]">
                        <div>
                            <div class="text-sm font-bold text-[#2C1810]">Pembayaran Online Instan</div>
                            <div class="text-xs text-[#7A6655] mt-0.5 leading-relaxed">QRIS, GoPay, OVO, Virtual Account BCA/BRI/BNI/Mandiri, Kartu Kredit</div>
                        </div>
                    </label>

                    <label data-method="cod"
                           class="payment-card flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200 <?= $selectedMethod === 'cod' ? 'border-[#C86D2D] bg-[#FAF7F2] shadow-sm' : 'border-[#E8DFD5] bg-white hover:border-[#C86D2D]/50' ?>">
                        <input type="radio" name="metode_pembayaran" value="cod" <?= $selectedMethod === 'cod' ? 'checked' : '' ?> class="mt-1 accent-[#C86D2D]">
                        <div>
                            <div class="text-sm font-bold text-[#2C1810]">Bayar di Tempat (COD)</div>
                            <div class="text-xs text-[#7A6655] mt-0.5 leading-relaxed">Bayar tunai langsung ke kurir saat pesanan tiba di lokasi Anda</div>
                        </div>
                    </label>
                </div>
            </div>

            <button id="checkoutSubmitBtn" type="submit"
                    class="btn-smooth w-full py-4 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] hover:shadow-lg hover:shadow-[#C86D2D]/25 text-white font-semibold text-sm shadow-md cursor-pointer">
                <?= $selectedMethod === 'cod' ? 'Buat Pesanan (Bayar di Tempat / COD)' : 'Buat Pesanan &amp; Lanjut Pembayaran' ?>
            </button>
        </form>

        <!-- Order Summary -->
        <div class="auth-stagger auth-delay-3 lg:sticky lg:top-24 bg-white rounded-3xl border border-[#E8DFD5] p-6 space-y-4 shadow-xs">
            <h2 class="font-serif text-xl font-bold text-[#2C1810]">Ringkasan Item</h2>
            <div class="divide-y divide-[#F3EDE4]">
                <?php foreach ($cart as $item): ?>
                    <div class="py-3 flex items-center justify-between gap-3 text-sm">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="<?= base_url('uploads/' . esc($item['gambar'])) ?>" alt="" class="w-12 h-10 rounded-lg object-cover bg-[#F3EDE4] shrink-0">
                            <div class="min-w-0">
                                <div class="font-semibold text-[#2C1810] truncate"><?= esc($item['nama_kopi']) ?></div>
                                <div class="text-xs text-[#7A6655]"><?= (int) $item['qty'] ?>x &bull; <?= esc($item['pilihan'] ?? 'Reguler') ?></div>
                            </div>
                        </div>
                        <div class="font-bold text-[#2C1810] shrink-0 whitespace-nowrap">
                            Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pt-3 border-t border-[#E8DFD5] flex justify-between items-center">
                <span class="text-sm font-semibold text-[#5C4738]">Total Bayar</span>
                <span class="text-xl font-bold text-[#C86D2D] whitespace-nowrap">Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    const cards = document.querySelectorAll('.payment-card');
    const radios = document.querySelectorAll('input[name="metode_pembayaran"]');
    const submitBtn = document.getElementById('checkoutSubmitBtn');

    function syncPaymentCards() {
        let activeValue = 'midtrans';
        radios.forEach(function (radio) {
            if (radio.checked) activeValue = radio.value;
        });

        cards.forEach(function (card) {
            const isSelected = card.getAttribute('data-method') === activeValue;
            if (isSelected) {
                card.classList.remove('border-[#E8DFD5]', 'bg-white');
                card.classList.add('border-[#C86D2D]', 'bg-[#FAF7F2]', 'shadow-sm');
            } else {
                card.classList.remove('border-[#C86D2D]', 'bg-[#FAF7F2]', 'shadow-sm');
                card.classList.add('border-[#E8DFD5]', 'bg-white');
            }
        });

        if (submitBtn) {
            submitBtn.textContent = activeValue === 'cod'
                ? 'Buat Pesanan (Bayar di Tempat / COD)'
                : 'Buat Pesanan & Lanjut Pembayaran';
        }
    }

    radios.forEach(function (radio) {
        radio.addEventListener('change', syncPaymentCards);
    });
    syncPaymentCards();
})();
</script>
<?= $this->endSection() ?>
