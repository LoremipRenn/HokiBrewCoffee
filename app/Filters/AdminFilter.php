<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        if ((int) session()->get('role_id') !== 1) {
            return redirect()->to('/')->with('error', 'Akses ditolak. Halaman ini khusus administrator.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
