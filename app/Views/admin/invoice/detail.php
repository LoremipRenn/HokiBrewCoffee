<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<a href="<?= base_url('admin/invoice') ?>" class="text-xs font-semibold text-[#7A6655] hover:text-[#C86D2D]">&larr; Kembali ke Daftar Invoice</a>
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-2 mb-6">
    <h1 class="font-serif text-3xl font-bold text-[#2C1810]">Detail Invoice <?= esc($invoice['order_id']) ?></h1>
    <a href="<?= base_url('admin/invoice/struk/' . $invoice['id']) ?>" target="_blank"
       class="btn-smooth gap-2 px-5 py-2.5 rounded-full bg-[#2C1810] hover:bg-[#C86D2D] text-white text-xs font-semibold self-start sm:self-auto">
        Cetak Struk
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-3xl border border-[#E8DFD5] p-6">
        <h2 class="font-serif text-xl font-bold text-[#2C1810] mb-4">Daftar Item</h2>
        <table class="w-full text-sm text-left">
            <thead class="border-b border-[#E8DFD5] text-xs uppercase text-[#7A6655]">
                <tr>
                    <th class="py-3">Produk</th>
                    <th class="py-3">Qty</th>
                    <th class="py-3">Harga</th>
                    <th class="py-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F3EDE4]">
                <?php $total = 0; foreach ($pesanan as $psn): $sub = (int) $psn['jumlah'] * (int) $psn['harga']; $total += $sub; ?>
                    <tr>
                        <td class="py-3">
                            <div class="font-semibold text-[#2C1810]"><?= esc($psn['nama_kopi']) ?></div>
                            <div class="text-xs text-[#7A6655]"><?= esc($psn['pilihan'] ?: 'Reguler') ?></div>
                        </td>
                        <td class="py-3"><?= (int) $psn['jumlah'] ?></td>
                        <td class="py-3">Rp <?= number_format((int) $psn['harga'], 0, ',', '.') ?></td>
                        <td class="py-3 text-right font-bold">Rp <?= number_format($sub, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="border-t border-[#E8DFD5]">
                    <td colspan="3" class="pt-4 font-bold text-[#4A2C20]">Grand Total</td>
                    <td class="pt-4 text-right text-lg font-bold text-[#C86D2D]">Rp <?= number_format($total, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="bg-white rounded-3xl border border-[#E8DFD5] p-6 space-y-5">
        <h2 class="font-serif text-xl font-bold text-[#2C1810]">Info &amp; Status</h2>
        <div class="text-sm space-y-2 text-[#5C4738]">
            <div><strong>Nama:</strong> <?= esc($invoice['nama']) ?></div>
            <div><strong>Telepon:</strong> <?= esc($invoice['no_telp'] ?? '-') ?></div>
            <div><strong>Alamat:</strong> <?= esc($invoice['alamat'] ?? '-') ?></div>
            <div><strong>Metode:</strong> <span class="uppercase font-semibold"><?= $invoice['metode_pembayaran'] === 'midtrans' ? 'Pembayaran Online' : esc($invoice['metode_pembayaran']) ?></span></div>
            <div><strong>Tgl Pesan:</strong> <?= esc($invoice['tgl_pesan']) ?></div>
        </div>

        <form action="<?= base_url('admin/invoice/status/' . $invoice['id']) ?>" method="post" class="pt-4 border-t border-[#F3EDE4] space-y-3">
            <?= csrf_field() ?>
            <label class="block text-xs font-bold uppercase text-[#4A2C20]">Ubah Status Pembayaran</label>
            <select name="status_pembayaran" class="w-full px-3 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm">
                <?php foreach (['pending', 'paid', 'failed', 'expired', 'cancelled'] as $st): ?>
                    <option value="<?= $st ?>" <?= $invoice['status_pembayaran'] === $st ? 'selected' : '' ?>><?= strtoupper($st) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="w-full py-2.5 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] text-white text-sm font-semibold cursor-pointer">
                Simpan Status
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
