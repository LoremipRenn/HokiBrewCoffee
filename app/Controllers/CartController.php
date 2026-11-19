<?php

namespace App\Controllers;

use App\Models\KopiModel;

class CartController extends BaseController
{
    public function index()
    {
        $cart  = session()->get('cart') ?? [];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return view('store/cart', [
            'title' => 'Keranjang Belanja — HokiBrewCoffee',
            'cart'  => $cart,
            'total' => $total,
        ]);
    }

    public function add(int $id)
    {
        $kopiModel = new KopiModel();
        $kopi      = $kopiModel->find($id);

        if (! $kopi) {
            return redirect()->to('/')->with('error', 'Produk tidak ditemukan.');
        }

        if ((int) $kopi['stok'] <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok ' . $kopi['nama_kopi'] . ' sedang habis.');
        }

        $qty     = max(1, (int) ($this->request->getPost('qty') ?? 1));
        $pilihan = trim((string) ($this->request->getPost('pilihan') ?? 'Reguler'));
        if ($pilihan === '') {
            $pilihan = 'Reguler';
        }

        $cart = session()->get('cart') ?? [];
        $key  = $id . '_' . md5($pilihan);

        $currentQty = isset($cart[$key]) ? (int) $cart[$key]['qty'] : 0;
        $newQty     = min((int) $kopi['stok'], $currentQty + $qty);

        $cart[$key] = [
            'key'       => $key,
            'id_kopi'   => (int) $kopi['id_kopi'],
            'nama_kopi' => $kopi['nama_kopi'],
            'kategori'  => $kopi['kategori'],
            'harga'     => (int) $kopi['harga'],
            'stok'      => (int) $kopi['stok'],
            'gambar'    => $kopi['gambar'],
            'pilihan'   => $pilihan,
            'qty'       => $newQty,
        ];

        session()->set('cart', $cart);

        return redirect()->back()->with('success', $kopi['nama_kopi'] . ' ditambahkan ke keranjang.');
    }

    public function update()
    {
        $key    = (string) $this->request->getPost('key');
        $action = (string) $this->request->getPost('action');
        $cart   = session()->get('cart') ?? [];

        if (isset($cart[$key])) {
            if ($action === 'remove') {
                unset($cart[$key]);
            } elseif ($action === 'inc') {
                $max = (int) ($cart[$key]['stok'] ?? 99);
                $cart[$key]['qty'] = min($max, $cart[$key]['qty'] + 1);
            } elseif ($action === 'dec') {
                $cart[$key]['qty']--;
                if ($cart[$key]['qty'] <= 0) {
                    unset($cart[$key]);
                }
            }
            session()->set('cart', $cart);
        }

        return redirect()->to('/keranjang');
    }

    public function clear()
    {
        session()->remove('cart');
        return redirect()->to('/keranjang')->with('success', 'Keranjang dikosongkan.');
    }
}
