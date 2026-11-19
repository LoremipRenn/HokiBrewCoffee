<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="font-serif text-3xl font-bold text-[#2C1810]">Kelola Menu Kopi</h1>
        <p class="text-sm text-[#7A6655] mt-1">Pantau pemasukan, ekspor laporan, dan kelola stok produk HokiBrewCoffee.</p>
    </div>
    <button type="button" onclick="openTambahModal()"
            class="btn-smooth gap-2 px-5 py-3 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] text-white text-sm font-semibold shadow-sm hover:shadow-md cursor-pointer">
        + Tambah Kopi
    </button>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-2xl border border-[#E8DFD5] p-5">
        <div class="text-xs font-bold uppercase tracking-wider text-[#9C8B7A]">Total Produk</div>
        <div class="text-2xl font-bold text-[#2C1810] mt-1"><?= (int) $totalProduk ?> Menu</div>
    </div>
    <div class="bg-white rounded-2xl border border-[#E8DFD5] p-5">
        <div class="text-xs font-bold uppercase tracking-wider text-[#9C8B7A]">Total Invoice</div>
        <div class="text-2xl font-bold text-[#2C1810] mt-1"><?= (int) $totalInvoice ?> Pesanan</div>
    </div>
    <div class="bg-white rounded-2xl border border-[#E8DFD5] p-5">
        <div class="text-xs font-bold uppercase tracking-wider text-[#9C8B7A]">Pendapatan Lunas</div>
        <div class="text-2xl font-bold text-emerald-700 mt-1">Rp <?= number_format((int) $totalPendapatan, 0, ',', '.') ?></div>
    </div>
</div>

<!-- Grafik Pemasukan -->
<div class="bg-white rounded-3xl border border-[#E8DFD5] p-6 mb-8 shadow-xs">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="font-serif text-xl font-bold text-[#2C1810]">Grafik Pemasukan</h2>
            <p class="text-xs text-[#7A6655] mt-0.5">Perbandingan total pesanan vs pemasukan lunas (Paid) per tanggal.</p>
        </div>
        <a href="<?= base_url('admin/invoice/export') ?>"
           class="btn-smooth gap-2 px-4 py-2 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold self-start sm:self-auto">
            Ekspor Pemasukan (.xls)
        </a>
    </div>
    <div class="h-64 w-full">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-3xl border border-[#E8DFD5] overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#FAF7F2] border-b border-[#E8DFD5] text-xs uppercase tracking-wider text-[#5C4738]">
                <tr>
                    <th class="px-5 py-4">Produk</th>
                    <th class="px-5 py-4">Kategori</th>
                    <th class="px-5 py-4">Harga</th>
                    <th class="px-5 py-4">Stok</th>
                    <th class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F3EDE4]">
                <?php foreach ($kopi as $item): ?>
                    <tr class="hover:bg-[#FAF7F2]/60 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <img src="<?= base_url('uploads/' . esc($item['gambar'])) ?>" alt="" class="w-12 h-10 rounded-lg object-cover bg-[#F3EDE4] shrink-0">
                                <div>
                                    <div class="font-bold text-[#2C1810]"><?= esc($item['nama_kopi']) ?></div>
                                    <div class="text-xs text-[#7A6655] line-clamp-1 max-w-xs"><?= esc($item['keterangan']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-3 py-1 rounded-full bg-[#FAF7F2] border border-[#E8DFD5] text-xs font-semibold text-[#4A2C20] whitespace-nowrap">
                                <?= esc($item['kategori']) ?>
                            </span>
                        </td>
                        <td class="px-5 py-4 font-semibold whitespace-nowrap">Rp <?= number_format((int) $item['harga'], 0, ',', '.') ?></td>
                        <td class="px-5 py-4"><?= (int) $item['stok'] ?></td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                <a href="<?= base_url('admin/kopi/edit/' . $item['id_kopi']) ?>" class="btn-smooth px-3.5 py-1.5 rounded-full bg-[#2C1810] text-white text-xs font-semibold hover:bg-[#C86D2D]">
                                    Edit
                                </a>
                                <form action="<?= base_url('admin/kopi/hapus/' . $item['id_kopi']) ?>" method="post" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-smooth px-3.5 py-1.5 rounded-full bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-600 hover:text-white cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-5 py-4 border-t border-[#F3EDE4] bg-[#FAF7F2]/50">
        <?= $pager->links('default', 'tailwind') ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<!-- Modal Tambah (Full-Viewport Backdrop + Smooth Animation) -->
<div id="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
    <div onclick="closeTambahModal()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div id="modalTambahCard" class="relative z-10 bg-white rounded-3xl border border-[#E8DFD5] max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 shadow-2xl transform scale-95 translate-y-4 transition-all duration-300 ease-out">
        <div class="flex items-center justify-between pb-4 mb-5 border-b border-[#F3EDE4]">
            <div>
                <h2 class="font-serif text-2xl font-bold text-[#2C1810]">Tambah Menu Kopi</h2>
                <p class="text-xs text-[#7A6655] mt-0.5">Lengkapi detail produk kopi baru untuk etalase toko.</p>
            </div>
            <button type="button" onclick="closeTambahModal()" class="w-9 h-9 rounded-full bg-[#FAF7F2] hover:bg-[#E8DFD5] text-[#4A2C20] flex items-center justify-center text-xl transition-colors cursor-pointer">&times;</button>
        </div>

        <form action="<?= base_url('admin/kopi/simpan') ?>" method="post" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Nama Kopi</label>
                <input type="text" name="nama_kopi" required placeholder="Contoh: Gayo Wine Arabika"
                       class="w-full px-4 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="2" required placeholder="Deskripsi rasa, aroma, dan karakter seduhan..."
                          class="w-full px-4 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Kategori</label>
                    <select name="kategori" class="w-full px-3 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D]">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Harga (Rp)</label>
                    <input type="number" name="harga" min="1000" required placeholder="35000"
                           class="w-full px-3 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D]">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Stok</label>
                    <input type="number" name="stok" min="0" required placeholder="50"
                           class="w-full px-3 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Foto Produk</label>
                <input type="file" name="gambar" accept="image/*" required
                       class="w-full text-xs text-[#5C4738] file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#2C1810] file:text-white hover:file:bg-[#C86D2D] file:cursor-pointer file:transition-colors">
            </div>

            <div class="pt-4 border-t border-[#F3EDE4] flex justify-end gap-3">
                <button type="button" onclick="closeTambahModal()" class="btn-smooth px-5 py-2.5 rounded-full border border-[#E8DFD5] text-sm font-semibold text-[#4A2C20] hover:border-[#2C1810] cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="btn-smooth px-6 py-2.5 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] hover:shadow-md text-white text-sm font-semibold cursor-pointer">
                    Simpan Menu
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openTambahModal() {
    const modal = document.getElementById('modalTambah');
    const card = document.getElementById('modalTambahCard');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100');
    card.classList.remove('scale-95', 'translate-y-4');
    card.classList.add('scale-100', 'translate-y-0');
}
function closeTambahModal() {
    const modal = document.getElementById('modalTambah');
    const card = document.getElementById('modalTambahCard');
    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.classList.remove('opacity-100');
    card.classList.add('scale-95', 'translate-y-4');
    card.classList.remove('scale-100', 'translate-y-0');
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeTambahModal();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [
                {
                    label: 'Pemasukan Lunas (Rp)',
                    data: <?= json_encode($chartPaid) ?>,
                    backgroundColor: '#C86D2D',
                    borderRadius: 8,
                    barPercentage: 0.55
                },
                {
                    label: 'Total Pesanan (Rp)',
                    data: <?= json_encode($chartTotal) ?>,
                    backgroundColor: '#E8DFD5',
                    borderRadius: 8,
                    barPercentage: 0.55
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (val) {
                            return 'Rp ' + Number(val).toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
})();
</script>
<?= $this->endSection() ?>
