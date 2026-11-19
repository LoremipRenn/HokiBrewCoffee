<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table         = 'tb_pesanan';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id_invoice',
        'id_kopi',
        'nama_kopi',
        'jumlah',
        'harga',
        'pilihan',
    ];

    public function getByInvoice(int $invoiceId): array
    {
        return $this->where('id_invoice', $invoiceId)->findAll();
    }
}
