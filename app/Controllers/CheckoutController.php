<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\KopiModel;
use App\Models\PesananModel;
use Config\Database;
use Config\Midtrans as MidtransConfig;

class CheckoutController extends BaseController
{
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return view('store/checkout', [
            'title' => 'Checkout & Pembayaran — HokiBrewCoffee',
            'cart'  => $cart,
            'total' => $total,
        ]);
    }

    public function process()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $rules = [
            'nama'              => 'required|min_length[3]|max_length[56]',
            'no_telp'           => 'permit_empty|max_length[25]',
            'alamat'            => 'required|min_length[5]|max_length[255]',
            'metode_pembayaran' => 'required|in_list[midtrans,cod]',
            'catatan'           => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $db           = Database::connect();
        $kopiModel    = new KopiModel();
        $invoiceModel = new InvoiceModel();
        $pesananModel = new PesananModel();

        $total       = 0;
        $itemDetails = [];

        $db->transStart();

        foreach ($cart as $item) {
            $product = $kopiModel->find($item['id_kopi']);
            if (! $product || (int) $product['stok'] < (int) $item['qty']) {
                $db->transRollback();
                return redirect()->to('/keranjang')
                    ->with('error', 'Stok untuk ' . ($item['nama_kopi'] ?? 'produk') . ' tidak mencukupi.');
            }

            $harga = (int) $product['harga'];
            $qty   = (int) $item['qty'];
            $total += $harga * $qty;

            $itemDetails[] = [
                'id'       => (string) $product['id_kopi'],
                'price'    => $harga,
                'quantity' => $qty,
                'name'     => mb_substr($product['nama_kopi'] . ' (' . ($item['pilihan'] ?? 'Reguler') . ')', 0, 50),
            ];
        }

        $orderId = 'HBC-' . date('YmdHis') . '-' . random_int(1000, 9999);
        $metode  = (string) $this->request->getPost('metode_pembayaran');
        $nama    = trim((string) $this->request->getPost('nama'));
        $noTelp  = trim((string) $this->request->getPost('no_telp'));
        $alamat  = trim((string) $this->request->getPost('alamat'));
        $catatan = trim((string) $this->request->getPost('catatan'));

        $invoiceId = $invoiceModel->insert([
            'order_id'          => $orderId,
            'user_id'           => session()->get('user_id'),
            'nama'              => $nama,
            'alamat'            => $alamat,
            'no_telp'           => $noTelp !== '' ? $noTelp : null,
            'catatan'           => $catatan !== '' ? $catatan : null,
            'tgl_pesan'         => date('Y-m-d H:i:s'),
            'batas_bayar'       => date('Y-m-d H:i:s', strtotime('+1 day')),
            'total_bayar'       => $total,
            'metode_pembayaran' => $metode,
            'status_pembayaran' => 'pending',
        ], true);

        foreach ($cart as $item) {
            $product = $kopiModel->find($item['id_kopi']);
            $pesananModel->insert([
                'id_invoice' => $invoiceId,
                'id_kopi'    => (int) $item['id_kopi'],
                'nama_kopi'  => $product['nama_kopi'],
                'jumlah'     => (int) $item['qty'],
                'harga'      => (int) $product['harga'],
                'pilihan'    => (string) ($item['pilihan'] ?? 'Reguler'),
            ]);

            $kopiModel->update($item['id_kopi'], [
                'stok' => max(0, (int) $product['stok'] - (int) $item['qty']),
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()
                ->with('error', 'Gagal memproses pesanan. Silakan coba lagi.');
        }

        // Generate Midtrans Snap Token if Midtrans is selected & configured
        if ($metode === 'midtrans') {
            $midtrans = new MidtransConfig();
            if ($midtrans->isConfigured()) {
                try {
                    \Midtrans\Config::$serverKey    = $midtrans->serverKey;
                    \Midtrans\Config::$isProduction = $midtrans->isProduction;
                    \Midtrans\Config::$isSanitized  = $midtrans->isSanitized;
                    \Midtrans\Config::$is3ds        = $midtrans->is3ds;

                    $params = [
                        'transaction_details' => [
                            'order_id'     => $orderId,
                            'gross_amount' => $total,
                        ],
                        'item_details'     => $itemDetails,
                        'customer_details' => [
                            'first_name' => $nama,
                            'phone'      => $noTelp,
                            'shipping_address' => [
                                'first_name' => $nama,
                                'phone'      => $noTelp,
                                'address'    => $alamat,
                            ],
                        ],
                        'callbacks' => [
                            'finish'   => base_url('checkout/finish'),
                            'unfinish' => base_url('checkout/finish'),
                            'error'    => base_url('checkout/finish'),
                        ],
                    ];

                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    $invoiceModel->update($invoiceId, ['snap_token' => $snapToken]);
                } catch (\Throwable $e) {
                    log_message('error', 'Midtrans Snap error: ' . $e->getMessage());
                }
            }
        }

        session()->remove('cart');

        return redirect()->to('/checkout/bayar/' . $orderId)
            ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    private function syncMidtransStatus(array $invoice): array
    {
        if (($invoice['metode_pembayaran'] ?? '') !== 'midtrans' || ($invoice['status_pembayaran'] ?? '') !== 'pending') {
            return $invoice;
        }

        $midtrans = new MidtransConfig();
        if (! $midtrans->isConfigured() || empty($invoice['order_id'])) {
            return $invoice;
        }

        try {
            \Midtrans\Config::$serverKey    = $midtrans->serverKey;
            \Midtrans\Config::$isProduction = $midtrans->isProduction;

            $status            = (object) \Midtrans\Transaction::status($invoice['order_id']);
            $transactionStatus = (string) ($status->transaction_status ?? '');
            $fraudStatus       = (string) ($status->fraud_status ?? '');

            $newStatus = match ($transactionStatus) {
                'capture'        => ($fraudStatus === 'accept' ? 'paid' : 'pending'),
                'settlement'     => 'paid',
                'pending'        => 'pending',
                'expire'         => 'expired',
                'cancel', 'deny' => 'failed',
                default          => $invoice['status_pembayaran'],
            };

            if ($newStatus !== $invoice['status_pembayaran']) {
                (new InvoiceModel())->update($invoice['id'], ['status_pembayaran' => $newStatus]);
                $invoice['status_pembayaran'] = $newStatus;
            }
        } catch (\Throwable $e) {
            // 404 from Midtrans simply means user hasn't opened Snap payment yet
            log_message('info', 'Midtrans status check skipped/pending for ' . $invoice['order_id'] . ': ' . $e->getMessage());
        }

        return $invoice;
    }

    public function finish()
    {
        $orderId = trim((string) $this->request->getGet('order_id'));
        if ($orderId === '') {
            return redirect()->to('/pesanan');
        }

        $invoiceModel = new InvoiceModel();
        $invoice      = $invoiceModel->findByOrderId($orderId);
        if (! $invoice) {
            return redirect()->to('/pesanan')->with('error', 'Invoice tidak ditemukan.');
        }

        $userId = (int) session()->get('user_id');
        $roleId = (int) session()->get('role_id');
        if ($roleId !== 1 && (int) $invoice['user_id'] !== $userId) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        $invoice   = $this->syncMidtransStatus($invoice);
        $newStatus = $invoice['status_pembayaran'];

        if ($newStatus === 'paid') {
            return redirect()->to('/checkout/bayar/' . $orderId)
                ->with('success', 'Pembayaran berhasil! Pesanan Anda telah lunas dan sedang disiapkan.');
        }
        if ($newStatus === 'pending') {
            return redirect()->to('/checkout/bayar/' . $orderId)
                ->with('success', 'Menunggu penyelesaian pembayaran Anda.');
        }
        return redirect()->to('/checkout/bayar/' . $orderId)
            ->with('error', 'Status pembayaran: ' . strtoupper($newStatus) . '.');
    }

    public function payment(string $orderId)
    {
        $invoiceModel = new InvoiceModel();
        $pesananModel = new PesananModel();

        $invoice = $invoiceModel->findByOrderId($orderId);
        if (! $invoice) {
            return redirect()->to('/')->with('error', 'Invoice tidak ditemukan.');
        }

        // Authorization check: only owner or admin can view
        $userId = (int) session()->get('user_id');
        $roleId = (int) session()->get('role_id');
        if ($roleId !== 1 && (int) $invoice['user_id'] !== $userId) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses ke invoice ini.');
        }

        $invoice  = $this->syncMidtransStatus($invoice);
        $midtrans = new MidtransConfig();

        return view('store/payment', [
            'title'              => 'Pembayaran #' . $orderId . ' — HokiBrewCoffee',
            'invoice'            => $invoice,
            'pesanan'            => $pesananModel->getByInvoice((int) $invoice['id']),
            'midtransConfigured' => $midtrans->isConfigured(),
            'midtransClientKey'  => $midtrans->clientKey,
            'snapJsUrl'          => $midtrans->snapJsUrl(),
        ]);
    }

    public function simulatePay(string $orderId)
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->back()->with('error', 'Simulasi hanya tersedia di mode development.');
        }

        $invoiceModel = new InvoiceModel();
        $invoice      = $invoiceModel->findByOrderId($orderId);

        if (! $invoice || (int) $invoice['user_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/')->with('error', 'Invoice tidak ditemukan.');
        }

        $invoiceModel->update($invoice['id'], ['status_pembayaran' => 'paid']);

        return redirect()->to('/checkout/bayar/' . $orderId)
            ->with('success', 'Pembayaran Sandbox berhasil disimulasikan! Pesanan Anda sedang disiapkan.');
    }

    public function orders()
    {
        $invoiceModel = new InvoiceModel();
        $userId       = (int) session()->get('user_id');

        $invoices = $invoiceModel->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('store/orders', [
            'title'    => 'Pesanan Saya — HokiBrewCoffee',
            'invoices' => $invoices,
        ]);
    }
}
