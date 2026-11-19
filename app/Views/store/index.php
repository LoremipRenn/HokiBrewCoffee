<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-[#2C1810] text-white">
    <div class="absolute inset-0">
        <img src="<?= base_url('images/hero-cafe.jpg') ?>" alt="HokiBrewCoffee Roastery" class="hero-bg-zoom w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#2C1810]/95 via-[#2C1810]/75 to-[#2C1810]/30"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 lg:py-28">
        <div class="max-w-2xl">
            <span class="hero-badge inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-xs border border-white/15 text-xs font-semibold tracking-wide uppercase text-[#F3C89B]">
                Specialty Coffee &amp; Artisanal Brews
            </span>
            <h1 class="hero-title mt-5 font-serif text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight tracking-tight">
                Crafted Roasts, <span class="italic text-[#E5985B]">Brewed Daily.</span>
            </h1>
            <p class="hero-desc mt-5 text-base sm:text-lg text-[#E8DFD5] leading-relaxed max-w-xl">
                Nikmati koleksi biji kopi pilihan Nusantara &amp; seduhan modern — dari Arabika single-origin, V60 manual brew, hingga signature cold brew.
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-4">
                <a href="#menu" class="hero-btn-primary btn-smooth group gap-2 px-6 py-3.5 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] text-white font-semibold text-sm shadow-lg shadow-black/25 hover:shadow-xl hover:shadow-[#C86D2D]/30">
                    Jelajahi Menu
                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-y-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
                <a href="<?= base_url('kategori/v60') ?>" class="hero-btn-secondary btn-smooth px-6 py-3.5 rounded-full bg-white/10 backdrop-blur-xs hover:bg-white/20 border border-white/25 hover:border-white/50 text-white font-semibold text-sm">
                    Manual Brew V60
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Bar -->
<section id="menu" class="reveal-on-scroll max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
        <!-- Category Pills -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="<?= base_url('/') ?>"
               class="btn-smooth px-4 py-2 rounded-full text-sm font-semibold <?= strtolower($activeCategory) === 'all' ? 'bg-[#2C1810] text-white shadow-sm' : 'bg-white text-[#4A2C20] border border-[#E8DFD5] hover:border-[#C86D2D] hover:text-[#C86D2D]' ?>">
                Semua Menu
            </a>
            <?php foreach ($categories as $cat): ?>
                <?php
                    $slug     = strtolower(str_replace(' ', '-', $cat));
                    $isActive = strtolower($activeCategory) === strtolower($cat);
                ?>
                <a href="<?= base_url('kategori/' . $slug) ?>"
                   class="btn-smooth px-4 py-2 rounded-full text-sm font-semibold <?= $isActive ? 'bg-[#2C1810] text-white shadow-sm' : 'bg-white text-[#4A2C20] border border-[#E8DFD5] hover:border-[#C86D2D] hover:text-[#C86D2D]' ?>">
                    <?= esc($cat) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Search Form -->
        <form action="<?= current_url() ?>" method="get" class="flex items-center gap-2 w-full lg:w-80">
            <div class="relative flex-1">
                <input type="text" name="q" value="<?= esc($searchQuery) ?>"
                       placeholder="Cari kopi favoritmu..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-full bg-white border border-[#E8DFD5] text-sm text-[#2C1810] placeholder-[#9C8B7A] focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
                <svg class="w-4 h-4 text-[#9C8B7A] absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="btn-smooth px-5 py-2.5 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] hover:shadow-md text-white text-sm font-semibold cursor-pointer">
                Cari
            </button>
        </form>
    </div>
</section>

<!-- Coffee Grid -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <?php if (empty($kopi)): ?>
        <div class="bg-white border border-[#E8DFD5] rounded-3xl p-12 text-center">
            <p class="font-serif text-2xl font-bold text-[#2C1810]">Menu tidak ditemukan</p>
            <p class="mt-2 text-sm text-[#7A6655]">Coba kata kunci lain atau lihat semua koleksi kopi kami.</p>
            <a href="<?= base_url('/') ?>" class="mt-5 inline-flex px-5 py-2.5 rounded-full bg-[#2C1810] text-white text-sm font-semibold">
                Lihat Semua Menu
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($kopi as $idx => $item): ?>
                <article style="transition-delay: <?= ($idx % 4) * 70 ?>ms"
                         class="reveal-on-scroll group bg-white rounded-3xl border border-[#E8DFD5] overflow-hidden flex flex-col hover:shadow-xl hover:shadow-[#2C1810]/8 hover:-translate-y-1.5 transition-all duration-300">
                    <!-- Image -->
                    <a href="<?= base_url('kopi/' . $item['id_kopi']) ?>" class="relative aspect-[4/3] overflow-hidden bg-[#F3EDE4] block">
                        <img src="<?= base_url('uploads/' . esc($item['gambar'])) ?>"
                             alt="<?= esc($item['nama_kopi']) ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-xs text-[11px] font-bold uppercase tracking-wider text-[#2C1810]">
                            <?= esc($item['kategori']) ?>
                        </span>
                        <?php if ((int) $item['stok'] <= 0): ?>
                            <span class="absolute top-3 right-3 px-3 py-1 rounded-full bg-red-600 text-white text-[11px] font-bold">
                                Habis
                            </span>
                        <?php endif; ?>
                    </a>

                    <!-- Body -->
                    <div class="p-5 flex-1 flex flex-col">
                        <h2 class="font-serif text-lg font-bold text-[#2C1810] leading-snug group-hover:text-[#C86D2D] transition-colors">
                            <a href="<?= base_url('kopi/' . $item['id_kopi']) ?>">
                                <?= esc($item['nama_kopi']) ?>
                            </a>
                        </h2>
                        <p class="mt-1.5 text-xs text-[#7A6655] line-clamp-2 leading-relaxed">
                            <?= esc($item['keterangan']) ?>
                        </p>

                        <!-- Price & Actions (Non-wrapping, crisp alignment) -->
                        <div class="mt-auto pt-4 border-t border-[#F3EDE4] space-y-3">
                            <div class="flex items-baseline justify-between gap-2">
                                <span class="text-xs text-[#9C8B7A] font-medium">Harga</span>
                                <span class="text-lg font-bold text-[#2C1810] whitespace-nowrap">
                                    Rp <?= number_format((int) $item['harga'], 0, ',', '.') ?>
                                </span>
                            </div>

                            <div class="grid <?= (int) $item['stok'] > 0 ? 'grid-cols-2' : 'grid-cols-1' ?> gap-2.5">
                                <a href="<?= base_url('kopi/' . $item['id_kopi']) ?>"
                                   class="btn-smooth h-10 px-4 rounded-full border border-[#E8DFD5] text-xs font-semibold text-[#4A2C20] hover:border-[#2C1810] hover:bg-[#FAF7F2]">
                                    Detail
                                </a>
                                <?php if ((int) $item['stok'] > 0): ?>
                                    <form action="<?= base_url('keranjang/tambah/' . $item['id_kopi']) ?>" method="post" class="w-full">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-smooth w-full h-10 px-4 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] hover:shadow-md hover:shadow-[#C86D2D]/25 text-white text-xs font-semibold cursor-pointer">
                                            + Keranjang
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?= $this->endSection() ?>
