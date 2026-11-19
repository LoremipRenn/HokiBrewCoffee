<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-3xl border border-[#E8DFD5] overflow-hidden grid grid-cols-1 md:grid-cols-2 shadow-sm">
        <!-- Visual Side (Repeat Loop Text Animation) -->
        <div class="relative flex min-h-[220px] md:min-h-0 flex-col justify-end p-6 sm:p-8 md:p-10 bg-[#2C1810] text-white overflow-hidden">
            <img src="<?= base_url('images/hero-cafe.jpg') ?>" alt="HokiBrewCoffee" class="hero-bg-zoom absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#2C1810]/90 via-[#2C1810]/45 to-[#2C1810]/25"></div>
            <div class="relative z-10">
                <span class="hero-badge inline-block text-xs font-bold uppercase tracking-widest text-[#E5985B]">Bergabung</span>
                <h2 class="hero-title mt-2 font-serif text-2xl md:text-3xl font-bold leading-snug">Mulai pengalaman kopi spesialti Anda.</h2>
                <p class="hero-desc mt-2 md:mt-3 text-xs sm:text-sm text-[#E8DFD5]">Buat akun dalam hitungan detik dan nikmati kemudahan pemesanan online.</p>
            </div>
        </div>

        <!-- Form Side (Smooth Staggered Entrance) -->
        <div class="p-8 sm:p-10">
            <h1 class="auth-stagger auth-delay-1 font-serif text-2xl sm:text-3xl font-bold text-[#2C1810]">Daftar Akun Baru</h1>
            <p class="auth-stagger auth-delay-2 mt-1.5 text-sm text-[#7A6655]">Gunakan password minimal 8 karakter.</p>

            <form action="<?= base_url('register') ?>" method="post" class="mt-6 space-y-4">
                <?= csrf_field() ?>

                <div class="auth-stagger auth-delay-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= esc(old('nama')) ?>" required
                           class="w-full px-4 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
                </div>

                <div class="auth-stagger auth-delay-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Username</label>
                    <input type="text" name="username" value="<?= esc(old('username')) ?>" required
                           class="w-full px-4 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
                </div>

                <div class="auth-stagger auth-delay-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Email (Opsional)</label>
                    <input type="email" name="email" value="<?= esc(old('email')) ?>"
                           class="w-full px-4 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
                </div>

                <div class="auth-stagger auth-delay-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Password</label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full px-4 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#4A2C20] mb-1.5">Konfirmasi</label>
                        <input type="password" name="password_confirm" required minlength="8"
                               class="w-full px-4 py-2.5 rounded-xl bg-[#FAF7F2] border border-[#E8DFD5] text-sm focus:outline-none focus:border-[#C86D2D] focus:ring-2 focus:ring-[#C86D2D]/20 transition-all">
                    </div>
                </div>

                <div class="auth-stagger auth-delay-5 pt-1">
                    <button type="submit" class="btn-smooth w-full py-3.5 rounded-full bg-[#2C1810] hover:bg-[#C86D2D] hover:shadow-lg hover:shadow-[#C86D2D]/25 text-white font-semibold text-sm shadow-md cursor-pointer">
                        Buat Akun
                    </button>
                </div>
            </form>

            <p class="auth-stagger auth-delay-6 mt-6 text-center text-sm text-[#7A6655]">
                Sudah punya akun?
                <a href="<?= base_url('login') ?>" class="font-bold text-[#C86D2D] hover:text-[#A8551E] hover:underline transition-colors">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
