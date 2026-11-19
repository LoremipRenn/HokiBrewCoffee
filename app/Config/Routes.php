<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(false);

// Public Storefront
$routes->get('/', 'HomeController::index');
$routes->get('kategori/(:segment)', 'HomeController::kategori/$1');
$routes->get('kopi/(:num)', 'HomeController::detail/$1');

// Auth (Guest Only)
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('login', 'AuthController::loginForm');
    $routes->post('login', 'AuthController::login');
    $routes->get('register', 'AuthController::registerForm');
    $routes->post('register', 'AuthController::register');
});

$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);

// Customer Cart, Checkout & Orders (Authenticated)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('keranjang', 'CartController::index');
    $routes->post('keranjang/tambah/(:num)', 'CartController::add/$1');
    $routes->get('keranjang/tambah/(:num)', 'CartController::add/$1');
    $routes->post('keranjang/update', 'CartController::update');
    $routes->post('keranjang/hapus', 'CartController::clear');

    $routes->get('checkout', 'CheckoutController::index');
    $routes->post('checkout/proses', 'CheckoutController::process');
    $routes->get('checkout/finish', 'CheckoutController::finish');
    $routes->get('checkout/bayar/(:segment)', 'CheckoutController::payment/$1');
    $routes->post('checkout/simulasi/(:segment)', 'CheckoutController::simulatePay/$1');
    $routes->get('pesanan', 'CheckoutController::orders');
});

// Midtrans Webhook (CSRF exempt in Config\Filters)
$routes->post('midtrans/notification', 'MidtransController::notification');

// Admin Panel (Admin Role Only)
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', 'Admin\KopiController::index');
    $routes->get('kopi', 'Admin\KopiController::index');
    $routes->post('kopi/simpan', 'Admin\KopiController::store');
    $routes->get('kopi/edit/(:num)', 'Admin\KopiController::edit/$1');
    $routes->post('kopi/update/(:num)', 'Admin\KopiController::update/$1');
    $routes->post('kopi/hapus/(:num)', 'Admin\KopiController::delete/$1');

    $routes->get('invoice', 'Admin\InvoiceController::index');
    $routes->get('invoice/export', 'Admin\InvoiceController::exportExcel');
    $routes->get('invoice/struk/(:num)', 'Admin\InvoiceController::receipt/$1');
    $routes->get('invoice/(:num)', 'Admin\InvoiceController::detail/$1');
    $routes->post('invoice/status/(:num)', 'Admin\InvoiceController::updateStatus/$1');
});
