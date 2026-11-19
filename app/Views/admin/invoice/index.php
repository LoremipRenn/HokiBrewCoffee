<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="font-serif text-3xl font-bold text-[#2C1810]">Invoice Pemesanan</h1>
        <p class="text-sm text-[#7A6655] mt-1">Pantau seluruh pesanan pelanggan, cetak struk, dan ekspor laporan Excel.</p>
    </div>
    <a href="<?= base_url('admin/invoice/export') ?>"
       class="btn-smooth gap-2 px-5 py-3 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-semibold self-start sm:self-auto">
        Ekspor Pemasukan (.xls)
    </a>
</div>

<div class="bg-white rounded-3xl border border-[#E8DFD5] overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#FAF7F2] border-b border-[#E8DFD5] text-xs uppercase tracking-wider text-[#5C4738]">
                <tr>
                    <th class="px-5 py-4">Order ID</th>
                    <th class="px-5 py-4">Pemesan</th>
                    <th class="px-5 py-4">Tanggal</th>
                    <th class="px-5 py-4">Total</th>
                    <th class="px-5 py-4">Metode</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F3EDE4]">
                <?php foreach ($invoices as $inv): ?>
                    <tr class="hover:bg-[#FAF7F2]/60 transition-colors">
                        <td class="px-5 py-4 font-bold text-[#2C1810] whitespace-nowrap"><?= esc($inv['order_id']) ?></td>
                        <td class="px-5 py-4"><?= esc($inv['nama']) ?></td>
                        <td class="px-5 py-4 text-xs text-[#7A6655] whitespace-nowrap"><?= esc($inv['tgl_pesan']) ?></td>
                        <td class="px-5 py-4 font-semibold whitespace-nowrap">Rp <?= number_format((int) $inv['total_bayar'], 0, ',', '.') ?></td>
                        <td class="px-5 py-4 uppercase text-xs font-bold"><?= $inv['metode_pembayaran'] === 'midtrans' ? 'ONLINE' : esc($inv['metode_pembayaran']) ?></td>
                        <td class="px-5 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold <?= $inv['status_pembayaran'] === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-800' ?>">
                                <?= strtoupper(esc($inv['status_pembayaran'])) ?>
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                <a href="<?= base_url('admin/invoice/' . $inv['id']) ?>" class="btn-smooth px-4 py-1.5 rounded-full bg-[#2C1810] text-white text-xs font-semibold hover:bg-[#C86D2D]">
                                    Detail
                                </a>
                                <a href="<?= base_url('admin/invoice/struk/' . $inv['id']) ?>" target="_blank" class="btn-smooth px-3.5 py-1.5 rounded-full border border-[#E8DFD5] text-[#4A2C20] text-xs font-semibold hover:border-[#C86D2D] hover:text-[#C86D2D]">
                                    Cetak Struk
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
