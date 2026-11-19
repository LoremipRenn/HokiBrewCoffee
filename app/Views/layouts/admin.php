<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin — HokiBrewCoffee') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="min-h-screen bg-[#FAF7F2] text-[#2C1810] antialiased flex overflow-x-hidden">

<!-- Mobile Backdrop -->
<div id="adminBackdrop" class="fixed inset-0 z-40 bg-black/45 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"></div>

<!-- Collapsible Sidebar -->
<aside id="adminSidebar"
       class="fixed md:sticky top-0 left-0 z-50 h-screen w-64 bg-[#2C1810] text-[#FAF7F2] flex flex-col shrink-0 -translate-x-full md:translate-x-0 transition-all duration-300 ease-out shadow-xl md:shadow-none overflow-hidden">
    <div class="p-5 border-b border-white/10 flex items-center justify-between gap-2 min-h-[73px]">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-3 overflow-hidden">
            <span class="w-9 h-9 rounded-xl bg-[#C86D2D] text-white flex items-center justify-center font-bold shrink-0 shadow-sm">HB</span>
            <div class="sidebar-label transition-opacity duration-200 whitespace-nowrap">
                <div class="font-serif text-lg font-bold leading-none">HokiBrew</div>
                <div class="text-[11px] text-[#C7B8AA] mt-1">Admin Roastery</div>
            </div>
        </a>
        <button id="mobileCloseBtn" type="button" class="md:hidden text-[#C7B8AA] hover:text-white text-xl leading-none cursor-pointer">&times;</button>
    </div>

    <nav class="flex-1 p-3 space-y-1.5 text-sm font-medium overflow-y-auto">
        <a href="<?= base_url('admin/kopi') ?>" title="Data Kopi"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 <?= str_contains(uri_string(), 'admin/kopi') || uri_string() === 'admin' ? 'bg-[#C86D2D] text-white shadow-sm' : 'text-[#E8DFD5] hover:bg-white/10 hover:translate-x-0.5' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 1v3M10 1v3M14 1v3"/>
            </svg>
            <span class="sidebar-label whitespace-nowrap transition-opacity duration-200">Data Kopi</span>
        </a>

        <a href="<?= base_url('admin/invoice') ?>" title="Invoice & Pesanan"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 <?= str_contains(uri_string(), 'admin/invoice') ? 'bg-[#C86D2D] text-white shadow-sm' : 'text-[#E8DFD5] hover:bg-white/10 hover:translate-x-0.5' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="sidebar-label whitespace-nowrap transition-opacity duration-200">Invoice &amp; Pesanan</span>
        </a>

        <a href="<?= base_url('admin/invoice/export') ?>" title="Ekspor Pemasukan Excel"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[#E8DFD5] hover:bg-white/10 hover:translate-x-0.5 transition-all duration-200">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="sidebar-label whitespace-nowrap transition-opacity duration-200">Ekspor Excel</span>
        </a>

        <a href="<?= base_url('/') ?>" title="Lihat Toko"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[#E8DFD5] hover:bg-white/10 hover:translate-x-0.5 transition-all duration-200">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="sidebar-label whitespace-nowrap transition-opacity duration-200">Lihat Toko</span>
        </a>
    </nav>

    <div class="p-3 border-t border-white/10">
        <div class="sidebar-label text-xs text-[#C7B8AA] px-2 mb-2 truncate">Masuk: <strong class="text-white"><?= esc(session()->get('nama')) ?></strong></div>
        <form action="<?= base_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit" title="Keluar"
                    class="w-full flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-white/10 hover:bg-red-500/80 text-white text-xs font-semibold transition-colors cursor-pointer">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="sidebar-label whitespace-nowrap">Keluar</span>
            </button>
        </form>
    </div>
</aside>

<!-- Main -->
<div class="flex-1 flex flex-col min-w-0">
    <!-- Topbar -->
    <header class="sticky top-0 z-30 bg-[#FAF7F2]/90 backdrop-blur-md border-b border-[#E8DFD5] px-4 sm:px-8 h-[73px] flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button id="sidebarToggleBtn" type="button" aria-label="Toggle Sidebar"
                    class="btn-smooth w-10 h-10 rounded-xl bg-white border border-[#E8DFD5] text-[#2C1810] hover:border-[#C86D2D] hover:text-[#C86D2D] cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16"/>
                </svg>
            </button>
            <span class="text-sm font-semibold text-[#5C4738] hidden sm:inline">Panel Manajemen HokiBrewCoffee</span>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="<?= base_url('admin/invoice/export') ?>"
               class="btn-smooth gap-1.5 px-4 py-2 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="hidden sm:inline">Ekspor Excel</span>
            </a>
        </div>
    </header>

    <main id="page-shell" class="page-shell flex-1 p-5 sm:p-8 max-w-7xl w-full mx-auto">
        <?php if ($msg = session()->getFlashdata('success')): ?>
            <div class="mb-5 px-4 py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-medium">
                <?= esc($msg) ?>
            </div>
        <?php endif; ?>
        <?php if ($err = session()->getFlashdata('error')): ?>
            <div class="mb-5 px-4 py-3 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-sm font-medium">
                <?= esc($err) ?>
            </div>
        <?php endif; ?>
        <?php if ($errs = session()->getFlashdata('errors')): ?>
            <div class="mb-5 px-4 py-3 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-sm">
                <ul class="list-disc list-inside space-y-0.5">
                    <?php foreach ((array) $errs as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>
</div>

<?= $this->renderSection('modals') ?>
<script>
(function () {
    const sidebar = document.getElementById('adminSidebar');
    const backdrop = document.getElementById('adminBackdrop');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const closeBtn = document.getElementById('mobileCloseBtn');
    const labels = document.querySelectorAll('.sidebar-label');

    function setDesktopCollapsed(collapsed) {
        if (collapsed) {
            sidebar.classList.remove('md:w-64', 'w-64');
            sidebar.classList.add('md:w-20', 'w-20');
            labels.forEach(el => el.classList.add('md:hidden'));
        } else {
            sidebar.classList.remove('md:w-20', 'w-20');
            sidebar.classList.add('md:w-64', 'w-64');
            labels.forEach(el => el.classList.remove('md:hidden'));
        }
        try { localStorage.setItem('hb_admin_sidebar_collapsed', collapsed ? '1' : '0'); } catch (_) {}
    }

    function openMobileDrawer() {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('opacity-0', 'pointer-events-none');
    }

    function closeMobileDrawer() {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
    }

    // Restore desktop state
    try {
        if (window.innerWidth >= 768 && localStorage.getItem('hb_admin_sidebar_collapsed') === '1') {
            setDesktopCollapsed(true);
        }
    } catch (_) {}

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (window.innerWidth < 768) {
                if (sidebar.classList.contains('-translate-x-full')) {
                    openMobileDrawer();
                } else {
                    closeMobileDrawer();
                }
            } else {
                const isCollapsed = sidebar.classList.contains('md:w-20');
                setDesktopCollapsed(!isCollapsed);
            }
        });
    }

    if (closeBtn) closeBtn.addEventListener('click', closeMobileDrawer);
    if (backdrop) backdrop.addEventListener('click', closeMobileDrawer);

    const shell = document.getElementById('page-shell');
    window.addEventListener('pageshow', function () {
        if (shell) shell.classList.remove('is-leaving');
    });
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link || !shell || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || link.target === '_blank') return;
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.includes('/export') || href.includes('/struk/')) return;
        let url;
        try { url = new URL(link.href, window.location.href); } catch (_) { return; }
        if (url.origin !== window.location.origin) return;
        e.preventDefault();
        shell.classList.add('is-leaving');
        setTimeout(function () { window.location.href = link.href; }, 190);
    });
})();
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
