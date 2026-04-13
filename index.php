<?php require_once 'config.php'; if (isLoggedIn()) { redirect('/native/pages/dashboard.php'); } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcaHub — Platform Pendidikan Digital</title>
    <meta name="description" content="AcaHub menghubungkan guru, siswa, dan orang tua dalam satu platform pendidikan yang mudah dan canggih.">
    <?php include 'layout/tailwind-config.php'; ?>
</head>
<body class="font-inter bg-white text-gray-900 overflow-x-hidden">

<!-- ── NAVBAR ── -->
<nav class="fixed top-0 w-full z-50 bg-white/85 backdrop-blur-xl border-b border-gray-100">
    <div class="max-w-[1200px] mx-auto px-6 h-16 flex items-center justify-between">
        <a href="index.php" class="text-2xl font-black text-brand-500 tracking-tight">AcaHub</a>
        <div class="hidden md:flex items-center gap-8">
            <a href="#features" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition-colors">Fitur</a>
            <a href="#how" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition-colors">Cara Kerja</a>
            <a href="auth/register.php" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition-colors">Daftar Sekolah</a>
        </div>
        <div class="flex items-center gap-4">
            <a href="auth/login.php" class="text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors">Masuk</a>
            <a href="auth/register.php" class="px-5 py-2.5 rounded-full text-sm font-semibold bg-accent-500 text-white shadow-[0_4px_14px_rgba(249,115,22,0.3)] hover:bg-accent-600 hover:shadow-[0_6px_20px_rgba(249,115,22,0.4)] hover:-translate-y-0.5 transition-all">Mulai Gratis</a>
        </div>
    </div>
</nav>

<!-- ── HERO ── -->
<section class="min-h-screen flex items-center pt-16 bg-gradient-to-br from-white to-brand-50">
    <div class="max-w-[1200px] mx-auto px-6 py-16 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div>
            <div class="inline-flex items-center gap-2 bg-brand-100 text-brand-700 px-4 py-1.5 rounded-full text-xs font-semibold mb-6 animate-fade-down">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse-dot"></span>
                Platform Pendidikan #1 Indonesia
            </div>
            <h1 class="text-5xl lg:text-[4rem] font-black leading-[1.08] tracking-tight animate-fade-up-delay-1">
                Pendidikan<br>
                <span class="text-brand-500">Terhubung</span><br>
                Mudah.
            </h1>
            <p class="mt-6 text-lg text-gray-500 leading-relaxed max-w-[480px] animate-fade-up-delay-2">
                AcaHub menjembatani guru, siswa, dan orang tua dengan nilai real-time, laporan mudah, dan komunikasi yang seamless.
            </p>
            <div class="mt-8 flex gap-4 flex-wrap animate-fade-up-delay-3">
                <a href="auth/register.php" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full font-bold text-[0.95rem] bg-accent-500 text-white shadow-[0_4px_20px_rgba(249,115,22,0.35)] hover:bg-accent-600 hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(249,115,22,0.4)] transition-all">
                    Mulai Sekarang
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
                <a href="auth/login.php" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full font-bold text-[0.95rem] border-2 border-brand-500 text-brand-600 hover:bg-brand-50 hover:-translate-y-0.5 transition-all">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    Masuk ke Akun
                </a>
            </div>
            <div class="flex gap-8 mt-12 animate-fade-up-delay-4">
                <div>
                    <div class="text-2xl font-black text-brand-600">500+</div>
                    <div class="text-xs text-gray-400 mt-0.5">Sekolah Terdaftar</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-brand-600">50K+</div>
                    <div class="text-xs text-gray-400 mt-0.5">Pengguna Aktif</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-brand-600">99%</div>
                    <div class="text-xs text-gray-400 mt-0.5">Kepuasan Pengguna</div>
                </div>
            </div>
        </div>

        <div class="hidden lg:grid grid-cols-2 gap-4 animate-fade-in-right relative">
            <div class="absolute w-[280px] h-[280px] rounded-full bg-brand-500/15 blur-[60px] -top-10 -left-10 pointer-events-none"></div>
            <div class="absolute w-[280px] h-[280px] rounded-full bg-accent-500/12 blur-[60px] -bottom-10 -right-10 pointer-events-none"></div>
            <div class="bg-white rounded-[1.25rem] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.07)] border border-gray-100 hover:shadow-[0_12px_40px_rgba(0,0,0,0.12)] hover:-translate-y-1 transition-all relative z-10">
                <div class="w-11 h-11 rounded-xl bg-brand-100 flex items-center justify-center mb-4">
                    <svg class="w-[22px] h-[22px] text-brand-600 stroke-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <h3 class="text-[0.95rem] font-bold text-gray-800">Nilai Real-time</h3>
                <p class="text-[0.8rem] text-gray-400 mt-1.5 leading-relaxed">Update nilai instan untuk siswa & orang tua</p>
            </div>
            <div class="bg-white rounded-[1.25rem] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.07)] border border-gray-100 hover:shadow-[0_12px_40px_rgba(0,0,0,0.12)] hover:-translate-y-1 transition-all relative z-10 mt-8">
                <div class="w-11 h-11 rounded-xl bg-accent-50 flex items-center justify-center mb-4">
                    <svg class="w-[22px] h-[22px] text-orange-700 stroke-orange-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>
                </div>
                <h3 class="text-[0.95rem] font-bold text-gray-800">Role-Based Access</h3>
                <p class="text-[0.8rem] text-gray-400 mt-1.5 leading-relaxed">Tampilan Admin, Guru, Siswa</p>
            </div>
            <div class="bg-white rounded-[1.25rem] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.07)] border border-gray-100 hover:shadow-[0_12px_40px_rgba(0,0,0,0.12)] hover:-translate-y-1 transition-all relative z-10">
                <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center mb-4">
                    <svg class="w-[22px] h-[22px] text-green-600 stroke-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <h3 class="text-[0.95rem] font-bold text-gray-800">Rapor Mudah</h3>
                <p class="text-[0.8rem] text-gray-400 mt-1.5 leading-relaxed">Rapor semester satu klik</p>
            </div>
            <div class="bg-white rounded-[1.25rem] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.07)] border border-gray-100 hover:shadow-[0_12px_40px_rgba(0,0,0,0.12)] hover:-translate-y-1 transition-all relative z-10 mt-8">
                <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center mb-4">
                    <svg class="w-[22px] h-[22px] text-purple-600 stroke-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                </div>
                <h3 class="text-[0.95rem] font-bold text-gray-800">Komunikasi</h3>
                <p class="text-[0.8rem] text-gray-400 mt-1.5 leading-relaxed">Hubungkan guru & orang tua</p>
            </div>
        </div>
    </div>
</section>

<!-- ── FEATURES ── -->
<section class="py-20 px-6 bg-gray-50" id="features">
    <div class="max-w-[1200px] mx-auto">
        <span class="inline-block text-xs font-bold tracking-widest uppercase text-brand-600 mb-4">✦ Fitur Unggulan</span>
        <h2 class="text-4xl font-black tracking-tight text-gray-900">Semua yang Anda Butuhkan</h2>
        <p class="mt-4 text-gray-500 text-lg max-w-[600px]">Dari manajemen nilai hingga komunikasi orang tua, AcaHub hadir lengkap untuk ekosistem pendidikan.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <?php
            $features = [
                ['icon'=>'📊','color'=>'#eff6ff','h'=>'Nilai & Rapor','p'=>'Input dan pantau nilai siswa secara real-time. Generate rapor otomatis di akhir semester dengan satu klik.'],
                ['icon'=>'🎓','color'=>'#f0fdf4','h'=>'Manajemen Kelas','p'=>'Kelola kelas, mata pelajaran, dan pendaftaran siswa dengan mudah dan terstruktur.'],
                ['icon'=>'📢','color'=>'#faf5ff','h'=>'Pengumuman','p'=>'Kirim pengumuman ke seluruh warga sekolah atau perkelas dalam hitungan detik.'],
                ['icon'=>'📅','color'=>'#fff7ed','h'=>'Absensi Digital','p'=>'Kelola kehadiran siswa secara digital. Orang tua notif otomatis saat anak tidak hadir.'],
                ['icon'=>'💬','color'=>'#fef2f2','h'=>'Pesan Internal','p'=>'Komunikasi langsung antara guru, siswa, dan orang tua dalam satu inbox terpadu.'],
                ['icon'=>'📈','color'=>'#f0fdfa','h'=>'Analitik Cerdas','p'=>'Dashboard visual dengan grafik distribusi nilai dan rata-rata per mata pelajaran.'],
            ];
            foreach($features as $f): ?>
            <div class="bg-white rounded-[1.25rem] p-8 border border-gray-100 hover:border-brand-200 hover:shadow-[0_8px_30px_rgba(8,145,178,0.1)] hover:-translate-y-1 transition-all">
                <div class="w-[52px] h-[52px] rounded-2xl flex items-center justify-center mb-5 text-2xl" style="background:<?= $f['color'] ?>">
                    <?= $f['icon'] ?>
                </div>
                <h3 class="text-base font-bold mb-2"><?= $f['h'] ?></h3>
                <p class="text-sm text-gray-500 leading-relaxed"><?= $f['p'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── HOW IT WORKS ── -->
<section class="py-20 px-6" id="how">
    <div class="max-w-[1200px] mx-auto">
        <span class="inline-block text-xs font-bold tracking-widest uppercase text-brand-600 mb-4">✦ Cara Kerja</span>
        <h2 class="text-4xl font-black tracking-tight text-gray-900">Mulai dalam 3 Langkah</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-12">
            <?php
            $steps = [
                ['n'=>1,'h'=>'Daftarkan Sekolah','p'=>'Isi formulir pendaftaran sekolah Anda. Proses verifikasi kurang dari 24 jam.'],
                ['n'=>2,'h'=>'Tambahkan Pengguna','p'=>'Import atau tambahkan guru dan siswa. Setiap pengguna mendapat akun otomatis.'],
                ['n'=>3,'h'=>'Atur Kelas & Mapel','p'=>'Buat struktur kelas dan mata pelajaran sesuai kurikulum sekolah Anda.'],
                ['n'=>4,'h'=>'Mulai Gunakan','p'=>'Semua fitur aktif! Guru input nilai, siswa & orang tua pantau perkembangan.'],
            ];
            foreach($steps as $s): ?>
            <div class="text-center">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 text-white text-xl font-black flex items-center justify-center mx-auto mb-4 shadow-[0_4px_16px_rgba(8,145,178,0.35)]">
                    <?= $s['n'] ?>
                </div>
                <h3 class="text-[0.95rem] font-bold mb-1.5"><?= $s['h'] ?></h3>
                <p class="text-[0.825rem] text-gray-500 leading-relaxed"><?= $s['p'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── CTA ── -->
<div class="max-w-[1200px] mx-auto px-6">
    <div class="rounded-[2rem] px-6 py-16 md:p-16 bg-gradient-to-br from-brand-600 to-brand-800 text-center relative overflow-hidden">
        <div class="absolute w-[400px] h-[400px] rounded-full bg-white/5 -top-[100px] -left-[100px] pointer-events-none"></div>
        <div class="absolute w-[300px] h-[300px] rounded-full bg-white/5 -bottom-20 -right-20 pointer-events-none"></div>
        <h2 class="text-3xl md:text-4xl font-black text-white relative z-10">Siap Transformasi Pendidikan Anda?</h2>
        <p class="text-white/75 mt-4 text-lg relative z-10">Bergabung dengan 500+ sekolah yang sudah merasakan manfaat AcaHub.</p>
        <a href="auth/register.php" class="inline-flex items-center gap-2 mt-8 px-10 py-3.5 rounded-full font-bold bg-white text-brand-700 shadow-[0_4px_20px_rgba(0,0,0,0.2)] hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(0,0,0,0.25)] transition-all relative z-10">
            Daftar Gratis Sekarang
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>
</div>

<!-- ── FOOTER ── -->
<footer class="py-8 px-6 border-t border-gray-100 text-center text-gray-400 text-sm mt-12">
    &copy; <?= date('Y') ?> AcaHub. Mendukung SDG 4 — Pendidikan Berkualitas.
</footer>

</body>
</html>
