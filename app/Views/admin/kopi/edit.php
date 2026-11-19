<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-5xl mx-auto">
    <div class="auth-stagger auth-delay-1 mb-6">
        <a href="<?= base_url('admin/kopi') ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#7A6655] hover:text-[#C86D2D] transition-colors">
            &larr; Kembali ke Data Kopi
        </a>
        <h1 class="font-serif text-3xl font-bold text-[#2C1810] mt-2">Edit Menu Kopi</h1>
        <p class="text-sm text-[#7A6655] mt-1">Perbarui informasi, harga, stok, atau foto produk <?= esc($kopi['nama_kopi']) ?>.</p>
    </div>

    <form action="<?= base_url('admin/kopi/update/' . $kopi['id_kopi']) ?>" method="post" enctype="multipart/form-data"
          class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <?= csrf_field() ?>

        <!-- Form Fields -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-[#E8DFD5] p-6 sm:p-8 space-y-5 shadow-xs">
            <div class="auth-stagger auth-delay-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Nama Kopi</label>
                <input type="text" name="nama_kopi" value="<?= esc(old('nama_kopi', $kopi['nama_kopi'])) ?>" required
                       class="w-full px-4 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
            </div>

            <div class="auth-stagger auth-delay-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="3" required
                          class="w-full px-4 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all"><?= esc(old('keterangan', $kopi['keterangan'])) ?></textarea>
            </div>

            <div class="auth-stagger auth-delay-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Kategori</label>
                    <select name="kategori" class="w-full px-3 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D]">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= esc($cat) ?>" <?= strtolower($kopi['kategori']) === strtolower($cat) ? 'selected' : '' ?>>
                                <?= esc($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Harga (Rp)</label>
                    <input type="number" name="harga" value="<?= esc(old('harga', $kopi['harga'])) ?>" min="1" required
                           class="w-full px-3 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D]">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Stok</label>
                    <input type="number" name="stok" value="<?= esc(old('stok', $kopi['stok'])) ?>" min="0" required
                           class="w-full px-3 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D]">
                </div>
            </div>

            <div class="auth-stagger auth-delay-5 pt-3 border-t border-[#F3EDE4] flex flex-wrap items-center gap-3">
                <button type="submit" class="btn-smooth px-7 py-3 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] hover:shadow-md text-white text-sm font-semibold cursor-pointer">
                    Simpan Perubahan
                </button>
                <a href="<?= base_url('admin/kopi') ?>" class="btn-smooth px-6 py-3 rounded-full border border-[#E8DFD5] text-sm font-semibold text-[#4A2C20] hover:border-[#2C1810]">
                    Batal
                </a>
            </div>
        </div>

        <!-- Live Image Preview Card -->
        <div class="auth-stagger auth-delay-4 bg-white rounded-3xl border border-[#E8DFD5] p-6 space-y-4 shadow-xs">
            <div class="text-xs font-bold uppercase tracking-wider text-[#4A2C20]">Foto Produk</div>
            <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-[#F3EDE4] border border-[#E8DFD5]">
                <img id="editPreviewImg" src="<?= base_url('uploads/' . esc($kopi['gambar'])) ?>" alt="<?= esc($kopi['nama_kopi']) ?>"
                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
            </div>
            <div>
                <input type="file" name="gambar" id="editGambarInput" accept="image/*"
                       class="w-full text-xs text-[#5C4738] file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#2C1810] file:text-white hover:file:bg-[#C86D2D] file:cursor-pointer file:transition-colors">
                <p class="text-[11px] text-[#7A6655] mt-2 leading-relaxed">Biarkan kosong jika tidak ingin mengganti gambar. Format: JPG, PNG, WebP (maks 2MB).</p>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    const input = document.getElementById('editGambarInput');
    const preview = document.getElementById('editPreviewImg');
    if (input && preview) {
        input.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }
})();
</script>
<?= $this->endSection() ?>
