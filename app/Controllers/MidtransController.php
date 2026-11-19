<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use Config\Midtrans as MidtransConfig;

class MidtransController extends BaseController
{
    public function notification()
    {
        $midtrans = new MidtransConfig();
        $payload  = $this->request->getJSON(true);

        if (! is_array($payload) || empty($payload['order_id'])) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Invalid payload']);
        }

        $orderId     = (string) ($payload['order_id'] ?? '');
        $statusCode  = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature   = (string) ($payload['signature_key'] ?? '');

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $midtrans->serverKey);
        if (! hash_equals($expectedSignature, $signature)) {
            log_message('warning', 'Midtrans invalid signature for order ' . $orderId);
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Invalid signature']);
        }

        $invoiceModel = new InvoiceModel();
        $invoice      = $invoiceModel->findByOrderId($orderId);

        if (! $invoice) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Order not found']);
        }

        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus       = (string) ($payload['fraud_status'] ?? '');

        $newStatus = match ($transactionStatus) {
            'capture'    => ($fraudStatus === 'accept' ? 'paid' : 'pending'),
            'settlement' => 'paid',
            'pending'    => 'pending',
            'expire'     => 'expired',
            'cancel', 'deny' => 'failed',
            default      => $invoice['status_pembayaran'],
        };

        $invoiceModel->update($invoice['id'], [
            'status_pembayaran' => $newStatus,
        ]);

        return $this->response->setJSON(['message' => 'OK', 'status' => $newStatus]);
    }
}
