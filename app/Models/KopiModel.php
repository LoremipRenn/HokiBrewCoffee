<?php

namespace App\Models;

use CodeIgniter\Model;

class KopiModel extends Model
{
    protected $table         = 'tb_kopi';
    protected $primaryKey    = 'id_kopi';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'nama_kopi',
        'keterangan',
        'kategori',
        'harga',
        'stok',
        'gambar',
    ];

    public function getByCategory(?string $category = null, ?string $search = null): array
    {
        $builder = $this->builder();

        if ($category !== null && $category !== '' && strtolower($category) !== 'all') {
            $builder->where('LOWER(kategori)', strtolower($category));
        }

        if ($search !== null && trim($search) !== '') {
            $term = trim($search);
            $builder->groupStart()
                ->like('nama_kopi', $term)
                ->orLike('keterangan', $term)
                ->orLike('kategori', $term)
                ->groupEnd();
        }

        return $builder->orderBy('id_kopi', 'DESC')->get()->getResultArray();
    }

    public function getCategories(): array
    {
        return ['Arabika', 'Robusta', 'V60', 'Latte', 'Cold Brew'];
    }
}
