<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'HokiBrewCoffee') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="min-h-screen flex flex-col bg-[#FAF7F2] text-[#2C1810] antialiased overflow-x-hidden">
<?php
    $cart      = session()->get('cart') ?? [];
    $cartCount = array_sum(array_column($cart, 'qty'));
    $cartTotal = 0;
    foreach ($cart as $cItem) {
        $cartTotal += ((int) ($cItem['harga'] ?? 0)) * ((int) ($cItem['qty'] ?? 0));
    }
    $isLogged  = (bool) session()->get('is_logged_in');
    $roleId    = (int) session()->get('role_id');
    $uriPath   = uri_string();
    $showFloatingCart = $cartCount > 0
        && ! str_starts_with($uriPath, 'keranjang')
        && ! str_starts_with($uriPath, 'checkout')
        && ! str_starts_with($uriPath, 'login')
        && ! str_starts_with($uriPath, 'register');
?>

<!-- Navbar -->
<header class="sticky top-0 z-40 bg-[#FAF7F2]/90 backdrop-blur-md border-b border-[#E8DFD5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Brand -->
            <a href="<?= base_url('/') ?>" class="flex items-center gap-2.5 group">
                <span class="w-9 h-9 rounded-xl bg-[#2C1810] text-[#FAF7F2] flex items-center justify-center shadow-sm group-hover:bg-[#C86D2D] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 1v3M10 1v3M14 1v3"/>
                    </svg>
                </span>
                <div>
                    <span class="font-serif text-xl font-bold tracking-tight text-[#2C1810]">HokiBrew</span>
                    <span class="hidden sm:inline-block text-xs font-medium text-[#C86D2D] ml-1">Coffee</span>
                </div>
            </a>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-[#4A2C20]">
                <a href="<?= base_url('/') ?>" class="hover:text-[#C86D2D] transition-colors">Menu</a>
                <a href="<?= base_url('kategori/arabika') ?>" class="hover:text-[#C86D2D] transition-colors">Arabika</a>
                <a href="<?= base_url('kategori/robusta') ?>" class="hover:text-[#C86D2D] transition-colors">Robusta</a>
                <a href="<?= base_url('kategori/v60') ?>" class="hover:text-[#C86D2D] transition-colors">V60</a>
                <a href="<?= base_url('kategori/latte') ?>" class="hover:text-[#C86D2D] transition-colors">Latte</a>
                <a href="<?= base_url('kategori/cold-brew') ?>" class="hover:text-[#C86D2D] transition-colors">Cold Brew</a>
            </nav>

            <!-- Right Actions -->
            <div class="flex items-center gap-2.5">
                <a href="<?= base_url('keranjang') ?>" class="btn-smooth relative px-4 py-2 rounded-full bg-white border border-[#E8DFD5] text-sm font-semibold text-[#2C1810] hover:border-[#C86D2D] hover:text-[#C86D2D] hover:shadow-md shadow-xs gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="hidden sm:inline">Keranjang</span>
                    <span class="inline-flex items-center justify-center min-w-5 h-5 px-1.5 text-xs font-bold rounded-full bg-[#C86D2D] text-white">
                        <?= $cartCount ?>
                    </span>
                </a>

                <?php if ($isLogged): ?>
                    <?php if ($roleId === 1): ?>
                        <a href="<?= base_url('admin/kopi') ?>" class="btn-smooth hidden sm:inline-flex px-4 py-2 rounded-full bg-[#2C1810] text-white text-sm font-semibold hover:bg-[#4A2C20] hover:shadow-md">
                            Admin Panel
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('pesanan') ?>" class="btn-smooth hidden sm:inline-flex px-4 py-2 rounded-full bg-white border border-[#E8DFD5] text-sm font-semibold text-[#2C1810] hover:border-[#C86D2D] hover:text-[#C86D2D] hover:shadow-md">
                            Pesanan Saya
                        </a>
                    <?php endif; ?>

                    <form action="<?= base_url('logout') ?>" method="post" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-smooth px-4 py-2 rounded-full text-sm font-semibold text-[#8C3B2B] hover:bg-red-50 cursor-pointer">
                            Keluar
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn-smooth hidden sm:inline-flex px-4 py-2 rounded-full text-sm font-semibold text-[#2C1810] hover:text-[#C86D2D]">
                        Masuk
                    </a>
                    <a href="<?= base_url('register') ?>" class="btn-smooth hidden sm:inline-flex px-4 py-2 rounded-full bg-[#2C1810] text-white text-sm font-semibold hover:bg-[#C86D2D] hover:shadow-md shadow-xs">
                        Daftar
                    </a>
                <?php endif; ?>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" type="button" aria-label="Buka Menu"
                        class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-full bg-white border border-[#E8DFD5] text-[#2C1810] hover:border-[#C86D2D] transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div id="mobileMenuDrawer" class="hidden md:hidden pb-4 pt-2 border-t border-[#E8DFD5] space-y-3">
            <nav class="grid grid-cols-2 gap-2 text-sm font-medium text-[#4A2C20]">
                <a href="<?= base_url('/') ?>" class="px-3 py-2 rounded-xl bg-white border border-[#E8DFD5] hover:border-[#C86D2D]">Semua Menu</a>
                <a href="<?= base_url('kategori/arabika') ?>" class="px-3 py-2 rounded-xl bg-white border border-[#E8DFD5] hover:border-[#C86D2D]">Arabika</a>
                <a href="<?= base_url('kategori/robusta') ?>" class="px-3 py-2 rounded-xl bg-white border border-[#E8DFD5] hover:border-[#C86D2D]">Robusta</a>
                <a href="<?= base_url('kategori/v60') ?>" class="px-3 py-2 rounded-xl bg-white border border-[#E8DFD5] hover:border-[#C86D2D]">V60</a>
                <a href="<?= base_url('kategori/latte') ?>" class="px-3 py-2 rounded-xl bg-white border border-[#E8DFD5] hover:border-[#C86D2D]">Latte</a>
                <a href="<?= base_url('kategori/cold-brew') ?>" class="px-3 py-2 rounded-xl bg-white border border-[#E8DFD5] hover:border-[#C86D2D]">Cold Brew</a>
            </nav>
            <div class="flex flex-wrap gap-2 pt-1">
                <?php if ($isLogged): ?>
                    <?php if ($roleId === 1): ?>
                        <a href="<?= base_url('admin/kopi') ?>" class="flex-1 text-center px-4 py-2.5 rounded-full bg-[#2C1810] text-white text-sm font-semibold">Admin Panel</a>
                    <?php else: ?>
                        <a href="<?= base_url('pesanan') ?>" class="flex-1 text-center px-4 py-2.5 rounded-full bg-white border border-[#E8DFD5] text-sm font-semibold text-[#2C1810]">Pesanan Saya</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="flex-1 text-center px-4 py-2.5 rounded-full bg-white border border-[#E8DFD5] text-sm font-semibold text-[#2C1810]">Masuk</a>
                    <a href="<?= base_url('register') ?>" class="flex-1 text-center px-4 py-2.5 rounded-full bg-[#2C1810] text-white text-sm font-semibold">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success') || session()->getFlashdata('error') || session()->getFlashdata('errors')): ?>
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4 space-y-2">
        <?php if ($msg = session()->getFlashdata('success')): ?>
            <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-medium">
                <span><?= esc($msg) ?></span>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold cursor-pointer">&times;</button>
            </div>
        <?php endif; ?>
        <?php if ($err = session()->getFlashdata('error')): ?>
            <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-sm font-medium">
                <span><?= esc($err) ?></span>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900 font-bold cursor-pointer">&times;</button>
            </div>
        <?php endif; ?>
        <?php if ($errs = session()->getFlashdata('errors')): ?>
            <div class="px-4 py-3 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-sm">
                <ul class="list-disc list-inside space-y-0.5">
                    <?php foreach ((array) $errs as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Main Content -->
<main id="page-shell" class="page-shell flex-1">
    <?= $this->renderSection('content') ?>
</main>

<?php if ($showFloatingCart): ?>
<!-- Floating Bottom Cart Bar -->
<div class="fixed bottom-4 inset-x-0 z-40 px-4 pointer-events-none">
    <div class="max-w-3xl mx-auto pointer-events-auto hero-btn-primary bg-[#2C1810] text-white rounded-2xl sm:rounded-full p-3 sm:px-6 sm:py-3.5 shadow-2xl shadow-black/30 border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="<?= base_url('keranjang') ?>" class="flex items-center gap-3 min-w-0">
            <span class="w-10 h-10 rounded-full bg-[#C86D2D] text-white flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </span>
            <div class="min-w-0">
                <div class="text-xs text-[#E8DFD5]"><?= (int) $cartCount ?> Item di Keranjang</div>
                <div class="text-base font-bold text-white whitespace-nowrap">Total: Rp <?= number_format($cartTotal, 0, ',', '.') ?></div>
            </div>
        </a>

        <div class="flex items-center gap-2 shrink-0">
            <a href="<?= base_url('keranjang') ?>"
               class="btn-smooth flex-1 sm:flex-initial px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-semibold">
                Keranjang
            </a>
            <a href="<?= base_url('checkout') ?>"
               class="btn-smooth flex-1 sm:flex-initial px-5 py-2 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] text-white text-xs font-semibold shadow-md">
                Checkout &rarr;
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Footer -->
<footer class="bg-[#2C1810] text-[#E8DFD5] mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <h3 class="font-serif text-xl font-bold text-white">HokiBrewCoffee</h3>
            <p class="mt-3 text-sm text-[#C7B8AA] leading-relaxed">
                Menyeduh biji kopi pilihan Nusantara &amp; Mancanegara dengan standar roastery modern. Segar diseduh setiap hari.
            </p>
        </div>
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Kategori Kopi</h4>
            <ul class="mt-3 space-y-2 text-sm text-[#C7B8AA]">
                <li><a href="<?= base_url('kategori/arabika') ?>" class="hover:text-[#C86D2D] transition-colors">Arabika Single Origin</a></li>
                <li><a href="<?= base_url('kategori/robusta') ?>" class="hover:text-[#C86D2D] transition-colors">Robusta Bold</a></li>
                <li><a href="<?= base_url('kategori/v60') ?>" class="hover:text-[#C86D2D] transition-colors">Manual Brew V60</a></li>
                <li><a href="<?= base_url('kategori/latte') ?>" class="hover:text-[#C86D2D] transition-colors">Signature Latte &amp; Cold Brew</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-wider text-white">Pembayaran Aman</h4>
            <p class="mt-3 text-sm text-[#C7B8AA]">
                Didukung oleh <span class="text-white font-semibold">Pembayaran Online Instan</span> (QRIS, GoPay, Virtual Account Bank, Kartu Kredit) &amp; COD.
            </p>
        </div>
    </div>
    <div class="border-t border-white/10 py-5 text-center text-xs text-[#A89686]">
        &copy; <?= date('Y') ?> HokiBrewCoffee.
    </div>
</footer>

<script>
(function () {
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobileDrawer = document.getElementById('mobileMenuDrawer');
    if (mobileBtn && mobileDrawer) {
        mobileBtn.addEventListener('click', function () {
            mobileDrawer.classList.toggle('hidden');
        });
    }

    const shell = document.getElementById('page-shell');

    // Restore visibility when navigating back from bfcache
    window.addEventListener('pageshow', function () {
        if (shell) shell.classList.remove('is-leaving');
    });

    // Smooth page leave transition on internal link clicks
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link || !shell) return;
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        if (link.target === '_blank' || link.hasAttribute('download')) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

        let url;
        try {
            url = new URL(link.href, window.location.href);
        } catch (_) {
            return;
        }
        if (url.origin !== window.location.origin) return;
        if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;

        e.preventDefault();
        shell.classList.add('is-leaving');
        setTimeout(function () {
            window.location.href = link.href;
        }, 210);
    });

    // Repeat & reset scroll reveal animation
    const revealItems = document.querySelectorAll('.reveal-on-scroll');
    if (revealItems.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                } else {
                    entry.target.classList.remove('is-visible');
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -30px 0px'
        });

        revealItems.forEach(function (el) {
            observer.observe(el);
        });
    } else {
        revealItems.forEach(function (el) {
            el.classList.add('is-visible');
        });
    }
})();
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
