<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Services;

class AuthController extends BaseController
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_MINUTES    = 15;

    public function loginForm()
    {
        return view('auth/login', [
            'title' => 'Masuk — HokiBrewCoffee',
        ]);
    }

    public function login()
    {
        // IP-level rate limiting (max 10 requests per minute per IP)
        $throttler = Services::throttler();
        $ipKey     = 'login_' . md5((string) $this->request->getIPAddress());
        if ($throttler->check($ipKey, 10, MINUTE) === false) {
            return redirect()->back()->withInput()
                ->with('error', 'Terlalu banyak percobaan login. Silakan tunggu 1 menit.');
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'password' => 'required|min_length[4]|max_length[128]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username  = trim((string) $this->request->getPost('username'));
        $password  = (string) $this->request->getPost('password');
        $userModel = new UserModel();
        $user      = $userModel->findByUsername($username);

        if (! $user) {
            // Constant-time-like dummy verify to mitigate user enumeration timing
            password_verify($password, '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG');
            return redirect()->back()->withInput()
                ->with('error', 'Username atau password salah.');
        }

        // Check account lockout
        if (! empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {
            $remaining = (int) ceil((strtotime($user['locked_until']) - time()) / 60);
            return redirect()->back()->withInput()
                ->with('error', "Akun terkunci sementara karena terlalu banyak percobaan gagal. Coba lagi dalam {$remaining} menit.");
        }

        if (! password_verify($password, $user['password'])) {
            $attempts = (int) ($user['login_attempts'] ?? 0) + 1;
            $update   = ['login_attempts' => $attempts];

            if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
                $update['locked_until'] = date('Y-m-d H:i:s', strtotime('+' . self::LOCKOUT_MINUTES . ' minutes'));
            }

            $userModel->update($user['id'], $update);

            $sisa = max(0, self::MAX_LOGIN_ATTEMPTS - $attempts);
            $msg  = $sisa > 0
                ? "Username atau password salah. Sisa percobaan: {$sisa}."
                : 'Akun dikunci selama ' . self::LOCKOUT_MINUTES . ' menit karena terlalu banyak percobaan gagal.';

            return redirect()->back()->withInput()->with('error', $msg);
        }

        // Rehash if algorithm/cost needs upgrade
        if (password_needs_rehash($user['password'], PASSWORD_BCRYPT)) {
            $userModel->update($user['id'], [
                'password' => password_hash($password, PASSWORD_BCRYPT),
            ]);
        }

        // Reset failed attempts on success
        $userModel->update($user['id'], [
            'login_attempts' => 0,
            'locked_until'   => null,
        ]);

        // Prevent session fixation
        session()->regenerate(true);
        session()->set([
            'is_logged_in' => true,
            'user_id'      => (int) $user['id'],
            'nama'         => $user['nama'],
            'username'     => $user['username'],
            'role_id'      => (int) $user['role_id'],
        ]);

        if ((int) $user['role_id'] === 1) {
            return redirect()->to('/admin/kopi')->with('success', 'Selamat datang kembali, ' . $user['nama'] . '!');
        }

        return redirect()->to('/')->with('success', 'Selamat datang kembali, ' . $user['nama'] . '!');
    }

    public function registerForm()
    {
        return view('auth/register', [
            'title' => 'Daftar Akun — HokiBrewCoffee',
        ]);
    }

    public function register()
    {
        $throttler = Services::throttler();
        $ipKey     = 'register_' . md5((string) $this->request->getIPAddress());
        if ($throttler->check($ipKey, 5, MINUTE) === false) {
            return redirect()->back()->withInput()
                ->with('error', 'Terlalu banyak percobaan pendaftaran. Silakan tunggu sebentar.');
        }

        $rules = [
            'nama'             => 'required|min_length[3]|max_length[50]',
            'username'         => 'required|alpha_numeric_dash|min_length[3]|max_length[50]|is_unique[tb_user.username]',
            'email'            => 'permit_empty|valid_email|max_length[120]|is_unique[tb_user.email]',
            'password'         => 'required|min_length[8]|max_length[128]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'username' => [
                'is_unique' => 'Username sudah digunakan.',
            ],
            'email' => [
                'is_unique' => 'Email sudah terdaftar.',
            ],
            'password' => [
                'min_length' => 'Password minimal 8 karakter agar akun lebih aman.',
            ],
            'password_confirm' => [
                'matches' => 'Konfirmasi password tidak cocok.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $email     = trim((string) $this->request->getPost('email'));

        $userModel->insert([
            'nama'       => trim((string) $this->request->getPost('nama')),
            'username'   => trim((string) $this->request->getPost('username')),
            'email'      => $email !== '' ? $email : null,
            'password'   => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
            'role_id'    => 2, // Selalu customer, tidak pernah dari input user
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')->with('success', 'Akun berhasil dibuat! Silakan masuk.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah keluar dengan aman.');
    }
}
