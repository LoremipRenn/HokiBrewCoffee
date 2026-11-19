<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="bg-white rounded-3xl border border-[#E8DFD5] overflow-hidden grid grid-cols-1 md:grid-cols-2 shadow-sm">
        <!-- Visual Side (Repeat Loop Text Animation) -->
        <div class="relative flex min-h-[220px] md:min-h-0 flex-col justify-end p-6 sm:p-8 md:p-10 bg-[#2C1810] text-white overflow-hidden">
            <img src="<?= base_url('images/hero-cafe.jpg') ?>" alt="HokiBrewCoffee" class="hero-bg-zoom absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#2C1810]/90 via-[#2C1810]/45 to-[#2C1810]/25"></div>
            <div class="relative z-10">
                <span class="hero-badge inline-block text-xs font-bold uppercase tracking-widest text-[#E5985B]">HokiBrewCoffee</span>
                <h2 class="hero-title mt-2 font-serif text-2xl md:text-3xl font-bold leading-snug">Selamat datang kembali di meja seduh kami.</h2>
                <p class="hero-desc mt-2 md:mt-3 text-xs sm:text-sm text-[#E8DFD5]">Masuk untuk memesan kopi favorit, melacak pengiriman, dan membayar dengan mudah secara online.</p>
            </div>
        </div>

        <!-- Form Side (Smooth Staggered Entrance) -->
        <div class="p-8 sm:p-10">
            <h1 class="auth-stagger auth-delay-1 font-serif text-2xl sm:text-3xl font-bold text-[#2C1810]">Masuk Akun</h1>
            <p class="auth-stagger auth-delay-2 mt-1.5 text-sm text-[#7A6655]">Dilindungi enkripsi password Bcrypt, CSRF, &amp; proteksi brute-force.</p>

            <form action="<?= base_url('login') ?>" method="post" class="mt-7 space-y-5">
                <?= csrf_field() ?>

                <div class="auth-stagger auth-delay-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-2">Username</label>
                    <input type="text" name="username" value="<?= esc(old('username')) ?>" required autocomplete="username"
                           placeholder="Masukkan username"
                           class="w-full px-4 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
                </div>

                <div class="auth-stagger auth-delay-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-2">Password</label>
                    <input type="password" name="password" required autocomplete="current-password"
                           placeholder="Masukkan password"
                           class="w-full px-4 py-3 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
                </div>

                <div class="auth-stagger auth-delay-5">
                    <button type="submit" class="btn-smooth w-full py-3.5 rounded-full bg-[#C86D2D] hover:bg-[#A8551E] hover:shadow-lg hover:shadow-[#C86D2D]/25 text-white font-semibold text-sm shadow-md cursor-pointer">
                        Masuk Sekarang
                    </button>
                </div>
            </form>

            <p class="auth-stagger auth-delay-6 mt-6 text-center text-sm text-[#7A6655]">
                Belum punya akun?
                <a href="<?= base_url('register') ?>" class="font-bold text-[#C86D2D] hover:text-[#A8551E] hover:underline transition-colors">Daftar di sini</a>
            </p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
