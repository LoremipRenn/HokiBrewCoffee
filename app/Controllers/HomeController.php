<?php

namespace App\Controllers;

use App\Models\KopiModel;

class HomeController extends BaseController
{
    public function index()
    {
        $kopiModel = new KopiModel();
        $category  = $this->request->getGet('kategori');
        $search    = $this->request->getGet('q');

        return view('store/index', [
            'title'           => 'HokiBrewCoffee — Specialty Coffee & Roastery',
            'kopi'            => $kopiModel->getByCategory($category, $search),
            'categories'      => $kopiModel->getCategories(),
            'activeCategory'  => $category ?: 'all',
            'searchQuery'     => $search ?? '',
        ]);
    }

    public function kategori(string $slug)
    {
        $map = [
            'arabika'   => 'Arabika',
            'robusta'   => 'Robusta',
            'v60'       => 'V60',
            'latte'     => 'Latte',
            'cold-brew' => 'Cold Brew',
            'cold_brew' => 'Cold Brew',
        ];

        $category  = $map[strtolower($slug)] ?? ucfirst($slug);
        $kopiModel = new KopiModel();
        $search    = $this->request->getGet('q');

        return view('store/index', [
            'title'          => $category . ' — HokiBrewCoffee',
            'kopi'           => $kopiModel->getByCategory($category, $search),
            'categories'     => $kopiModel->getCategories(),
            'activeCategory' => $category,
            'searchQuery'    => $search ?? '',
        ]);
    }

    public function detail(int $id)
    {
        $kopiModel = new KopiModel();
        $item      = $kopiModel->find($id);

        if (! $item) {
            return redirect()->to('/')->with('error', 'Produk kopi tidak ditemukan.');
        }

        $related = $kopiModel->where('kategori', $item['kategori'])
            ->where('id_kopi !=', $id)
            ->findAll(4);

        return view('store/detail', [
            'title'   => $item['nama_kopi'] . ' — HokiBrewCoffee',
            'kopi'    => $item,
            'related' => $related,
        ]);
    }
}
