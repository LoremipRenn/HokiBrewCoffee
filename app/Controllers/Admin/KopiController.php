<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InvoiceModel;
use App\Models\KopiModel;

class KopiController extends BaseController
{
    public function index()
    {
        $kopiModel    = new KopiModel();
        $invoiceModel = new InvoiceModel();

        $totalPendapatan = $invoiceModel->selectSum('total_bayar')
            ->where('status_pembayaran', 'paid')
            ->first()['total_bayar'] ?? 0;

        // Data grafik pemasukan per tanggal
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT DATE(tgl_pesan) AS tanggal,
                   SUM(CASE WHEN status_pembayaran = 'paid' THEN total_bayar ELSE 0 END) AS lunas,
                   SUM(total_bayar) AS total_semua
            FROM tb_invoice
            GROUP BY DATE(tgl_pesan)
            ORDER BY tanggal ASC
            LIMIT 14
        ")->getResultArray();

        $chartLabels = [];
        $chartPaid   = [];
        $chartTotal  = [];
        foreach ($rows as $row) {
            $chartLabels[] = date('d M', strtotime($row['tanggal']));
            $chartPaid[]   = (int) $row['lunas'];
            $chartTotal[]  = (int) $row['total_semua'];
        }

        return view('admin/kopi/index', [
            'title'           => 'Kelola Menu Kopi — Admin HokiBrewCoffee',
            'kopi'            => $kopiModel->orderBy('id_kopi', 'DESC')->paginate(6),
            'pager'           => $kopiModel->pager,
            'categories'      => $kopiModel->getCategories(),
            'totalProduk'     => $kopiModel->countAllResults(),
            'totalInvoice'    => $invoiceModel->countAllResults(),
            'totalPendapatan' => (int) $totalPendapatan,
            'chartLabels'     => $chartLabels,
            'chartPaid'       => $chartPaid,
            'chartTotal'      => $chartTotal,
        ]);
    }

    public function store()
    {
        $rules = [
            'nama_kopi'  => 'required|min_length[2]|max_length[120]',
            'keterangan' => 'required|min_length[3]|max_length[500]',
            'kategori'   => 'required|in_list[Arabika,Robusta,V60,Latte,Cold Brew]',
            'harga'      => 'required|integer|greater_than[0]',
            'stok'       => 'required|integer|greater_than_equal_to[0]',
            'gambar'     => 'uploaded[gambar]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]|max_size[gambar,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('gambar');
        $name = $file->getRandomName();
        $file->move(FCPATH . 'uploads', $name);

        $kopiModel = new KopiModel();
        $kopiModel->insert([
            'nama_kopi'  => trim((string) $this->request->getPost('nama_kopi')),
            'keterangan' => trim((string) $this->request->getPost('keterangan')),
            'kategori'   => (string) $this->request->getPost('kategori'),
            'harga'      => (int) $this->request->getPost('harga'),
            'stok'       => (int) $this->request->getPost('stok'),
            'gambar'     => $name,
        ]);

        return redirect()->to('/admin/kopi')->with('success', 'Menu kopi baru berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $kopiModel = new KopiModel();
        $kopi      = $kopiModel->find($id);

        if (! $kopi) {
            return redirect()->to('/admin/kopi')->with('error', 'Produk tidak ditemukan.');
        }

        return view('admin/kopi/edit', [
            'title'      => 'Edit ' . $kopi['nama_kopi'] . ' — Admin HokiBrewCoffee',
            'kopi'       => $kopi,
            'categories' => $kopiModel->getCategories(),
        ]);
    }

    public function update(int $id)
    {
        $kopiModel = new KopiModel();
        $existing  = $kopiModel->find($id);

        if (! $existing) {
            return redirect()->to('/admin/kopi')->with('error', 'Produk tidak ditemukan.');
        }

        $rules = [
            'nama_kopi'  => 'required|min_length[2]|max_length[120]',
            'keterangan' => 'required|min_length[3]|max_length[500]',
            'kategori'   => 'required|in_list[Arabika,Robusta,V60,Latte,Cold Brew]',
            'harga'      => 'required|integer|greater_than[0]',
            'stok'       => 'required|integer|greater_than_equal_to[0]',
        ];

        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $rules['gambar'] = 'is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]|max_size[gambar,2048]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $imageName = $existing['gambar'];
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $imageName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $imageName);

            if (! empty($existing['gambar']) && is_file(FCPATH . 'uploads/' . $existing['gambar'])) {
                @unlink(FCPATH . 'uploads/' . $existing['gambar']);
            }
        }

        $kopiModel->update($id, [
            'nama_kopi'  => trim((string) $this->request->getPost('nama_kopi')),
            'keterangan' => trim((string) $this->request->getPost('keterangan')),
            'kategori'   => (string) $this->request->getPost('kategori'),
            'harga'      => (int) $this->request->getPost('harga'),
            'stok'       => (int) $this->request->getPost('stok'),
            'gambar'     => $imageName,
        ]);

        return redirect()->to('/admin/kopi')->with('success', 'Data kopi berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $kopiModel = new KopiModel();
        $existing  = $kopiModel->find($id);

        if ($existing) {
            if (! empty($existing['gambar']) && is_file(FCPATH . 'uploads/' . $existing['gambar'])) {
                @unlink(FCPATH . 'uploads/' . $existing['gambar']);
            }
            $kopiModel->delete($id);
        }

        return redirect()->to('/admin/kopi')->with('success', 'Produk kopi berhasil dihapus.');
    }
}
