<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table         = 'tb_invoice';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'order_id',
        'user_id',
        'nama',
        'alamat',
        'no_telp',
        'catatan',
        'tgl_pesan',
        'batas_bayar',
        'total_bayar',
        'metode_pembayaran',
        'status_pembayaran',
        'snap_token',
    ];

    public function findByOrderId(string $orderId): ?array
    {
        return $this->where('order_id', $orderId)->first();
    }
}
