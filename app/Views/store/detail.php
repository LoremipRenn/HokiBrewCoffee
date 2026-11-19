<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="text-xs font-medium text-[#7A6655] mb-6 flex items-center gap-2">
        <a href="<?= base_url('/') ?>" class="hover:text-[#C86D2D]">Beranda</a>
        <span>/</span>
        <a href="<?= base_url('kategori/' . strtolower(str_replace(' ', '-', $kopi['kategori']))) ?>" class="hover:text-[#C86D2D]"><?= esc($kopi['kategori']) ?></a>
        <span>/</span>
        <span class="text-[#2C1810] font-semibold"><?= esc($kopi['nama_kopi']) ?></span>
    </nav>

    <div class="bg-white rounded-3xl border border-[#E8DFD5] p-6 sm:p-10 grid grid-cols-1 lg:grid-cols-2 gap-10 items-start shadow-xs">
        <!-- Product Image -->
        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-[#F3EDE4]">
            <img src="<?= base_url('uploads/' . esc($kopi['gambar'])) ?>"
                 alt="<?= esc($kopi['nama_kopi']) ?>"
                 class="w-full h-full object-cover">
        </div>

        <!-- Product Info -->
        <div class="flex flex-col">
            <span class="inline-flex self-start px-3.5 py-1 rounded-full bg-[#FAF7F2] border border-[#E8DFD5] text-xs font-bold uppercase tracking-wider text-[#C86D2D]">
                <?= esc($kopi['kategori']) ?>
            </span>

            <h1 class="mt-4 font-serif text-3xl sm:text-4xl font-bold text-[#2C1810]">
                <?= esc($kopi['nama_kopi']) ?>
            </h1>

            <div class="mt-4 flex items-center gap-4">
                <span class="text-2xl sm:text-3xl font-bold text-[#C86D2D]">
                    Rp <?= number_format((int) $kopi['harga'], 0, ',', '.') ?>
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= (int) $kopi['stok'] > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
                    <?= (int) $kopi['stok'] > 0 ? 'Stok tersedia: ' . (int) $kopi['stok'] : 'Stok Habis' ?>
                </span>
            </div>

            <p class="mt-6 text-sm sm:text-base text-[#5C4738] leading-relaxed">
                <?= esc($kopi['keterangan']) ?>
            </p>

            <?php if ((int) $kopi['stok'] > 0): ?>
                <form action="<?= base_url('keranjang/tambah/' . $kopi['id_kopi']) ?>" method="post" class="mt-8 space-y-5">
                    <?= csrf_field() ?>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-2">Pilihan Penyajian / Gilingan</label>
                        <select name="pilihan" class="w-full sm:w-72 px-4 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm font-medium text-[#2C1810] focus:outline-none focus:border-[#C86D2D]">
                            <option value="Hot / Reguler">Hot / Reguler</option>
                            <option value="Iced">Iced</option>
                            <option value="Whole Bean (Biji Utuh)">Whole Bean (Biji Utuh)</option>
                            <option value="Ground Fine (Espresso)">Ground Fine (Espresso)</option>
                            <option value="Ground Medium (V60 / Pour Over)">Ground Medium (V60 / Pour Over)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-2">Jumlah</label>
                        <input type="number" name="qty" value="1" min="1" max="<?= (int) $kopi['stok'] ?>"
                               class="w-28 px-4 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm font-bold text-[#2C1810] focus:outline-none focus:border-[#C86D2D]">
                    </div>

                    <div class="pt-2 flex flex-wrap gap-3">
                        <button type="submit" class="px-7 py-3.5 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] text-white font-semibold text-sm transition-colors shadow-md cursor-pointer">
                            Tambah ke Keranjang
                        </button>
                        <a href="<?= base_url('/') ?>" class="px-6 py-3.5 rounded-full border border-[#E8DFD5] text-[#4A2C20] font-semibold text-sm hover:border-[#2C1810] transition-colors">
                            Kembali ke Menu
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <div class="mt-8">
                    <a href="<?= base_url('/') ?>" class="inline-flex px-6 py-3 rounded-full bg-[#2C1810] text-white text-sm font-semibold">
                        Lihat Menu Lainnya
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (! empty($related)): ?>
        <div class="mt-14">
            <h2 class="font-serif text-2xl font-bold text-[#2C1810] mb-6">Kopi Serupa</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($related as $rel): ?>
                    <a href="<?= base_url('kopi/' . $rel['id_kopi']) ?>" class="bg-white rounded-2xl border border-[#E8DFD5] overflow-hidden hover:shadow-md transition-shadow">
                        <div class="aspect-[4/3] bg-[#F3EDE4]">
                            <img src="<?= base_url('uploads/' . esc($rel['gambar'])) ?>" alt="<?= esc($rel['nama_kopi']) ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="font-serif font-bold text-[#2C1810]"><?= esc($rel['nama_kopi']) ?></h3>
                            <p class="mt-1 text-sm font-semibold text-[#C86D2D]">Rp <?= number_format((int) $rel['harga'], 0, ',', '.') ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
