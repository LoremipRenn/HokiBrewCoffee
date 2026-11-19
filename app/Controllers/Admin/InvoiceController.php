<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InvoiceModel;
use App\Models\PesananModel;

class InvoiceController extends BaseController
{
    public function index()
    {
        $invoiceModel = new InvoiceModel();

        return view('admin/invoice/index', [
            'title'    => 'Daftar Invoice — Admin HokiBrewCoffee',
            'invoices' => $invoiceModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function detail(int $id)
    {
        $invoiceModel = new InvoiceModel();
        $pesananModel = new PesananModel();

        $invoice = $invoiceModel->find($id);
        if (! $invoice) {
            return redirect()->to('/admin/invoice')->with('error', 'Invoice tidak ditemukan.');
        }

        return view('admin/invoice/detail', [
            'title'   => 'Detail Invoice #' . $invoice['id'] . ' — Admin HokiBrewCoffee',
            'invoice' => $invoice,
            'pesanan' => $pesananModel->getByInvoice($id),
        ]);
    }

    public function updateStatus(int $id)
    {
        $status = (string) $this->request->getPost('status_pembayaran');
        if (! in_array($status, ['pending', 'paid', 'failed', 'expired', 'cancelled'], true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $invoiceModel = new InvoiceModel();
        if ($invoiceModel->find($id)) {
            $invoiceModel->update($id, ['status_pembayaran' => $status]);
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function receipt(int $id)
    {
        $invoiceModel = new InvoiceModel();
        $pesananModel = new PesananModel();

        $invoice = $invoiceModel->find($id);
        if (! $invoice) {
            return redirect()->to('/admin/invoice')->with('error', 'Invoice tidak ditemukan.');
        }

        return view('admin/invoice/receipt', [
            'title'   => 'Struk #' . ($invoice['order_id'] ?: $invoice['id']),
            'invoice' => $invoice,
            'pesanan' => $pesananModel->getByInvoice($id),
        ]);
    }

    public function exportExcel()
    {
        $invoiceModel = new InvoiceModel();
        $invoices     = $invoiceModel->orderBy('id', 'DESC')->findAll();

        $filename = 'laporan-pemasukan-hokibrew-' . date('Ymd-His') . '.xls';

        $totalLunas = 0;
        $totalSemua = 0;

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel"><head><meta charset="UTF-8"></head><body>';
        $html .= '<table border="1" cellpadding="6" cellspacing="0">';
        $html .= '<thead>';
        $html .= '<tr><th colspan="7" style="background:#2C1810;color:#ffffff;font-size:14px;">LAPORAN PEMASUKAN HOKIBREWCOFFEE</th></tr>';
        $html .= '<tr style="background:#FAF7F2;font-weight:bold;"><th>No</th><th>Order ID</th><th>Tanggal</th><th>Pemesan</th><th>Metode</th><th>Status</th><th>Total (Rp)</th></tr>';
        $html .= '</thead><tbody>';

        foreach ($invoices as $i => $inv) {
            $bayar       = (int) ($inv['total_bayar'] ?? 0);
            $totalSemua += $bayar;
            if (($inv['status_pembayaran'] ?? '') === 'paid') {
                $totalLunas += $bayar;
            }
            $html .= '<tr>';
            $html .= '<td>' . ($i + 1) . '</td>';
            $html .= '<td>' . esc($inv['order_id'] ?? ('HBC-' . $inv['id'])) . '</td>';
            $html .= '<td>' . esc($inv['tgl_pesan']) . '</td>';
            $html .= '<td>' . esc($inv['nama']) . '</td>';
            $html .= '<td>' . strtoupper(esc($inv['metode_pembayaran'] ?? 'midtrans')) . '</td>';
            $html .= '<td>' . strtoupper(esc($inv['status_pembayaran'] ?? 'pending')) . '</td>';
            $html .= '<td align="right">' . $bayar . '</td>';
            $html .= '</tr>';
        }

        $html .= '<tr style="font-weight:bold;background:#F3EDE4;"><td colspan="6" align="right">Total Pemasukan Lunas (PAID)</td><td align="right">' . $totalLunas . '</td></tr>';
        $html .= '<tr style="font-weight:bold;"><td colspan="6" align="right">Total Seluruh Pesanan</td><td align="right">' . $totalSemua . '</td></tr>';
        $html .= '</tbody></table></body></html>';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0')
            ->setBody($html);
    }
}
