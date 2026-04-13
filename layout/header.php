<?php
// layout/header.php  — dipanggil di awal setiap halaman authenticated
// Pemanggil harus set: $pageTitle (string), $activePage (string)
require_once __DIR__ . '/../config.php';
requireLogin();
$user = currentUser();
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Dashboard') ?> — AcaHub</title>
    <?php include __DIR__ . '/tailwind-config.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <style>
        /* Sidebar mobile transition */
        .sidebar-mobile { transform: translateX(-100%); transition: transform 0.25s; }
        .sidebar-mobile.open { transform: translateX(0); }
        @media(min-width:1024px) { .sidebar-mobile { transform: translateX(0); } }
        /* Overlay */
        .sidebar-overlay { display:none; }
        .sidebar-overlay.open { display:block; }
    </style>
</head>
<body class="font-inter bg-gray-50 text-gray-900">

<!-- SIDEBAR -->
<aside class="sidebar-mobile fixed inset-y-0 left-0 w-64 z-40 bg-white border-r border-gray-100 flex flex-col" id="sidebar">
    <div class="h-16 flex items-center px-6 border-b border-gray-100">
        <a href="/native/pages/dashboard.php" class="text-2xl font-black text-brand-500 tracking-tight">AcaHub</a>
    </div>

    <nav class="flex-1 p-4 overflow-y-auto">
        <?php
        $isAdmin   = ($user['role'] === 'admin');
        $isTeacher = ($user['role'] === 'teacher');
        $isStudent = ($user['role'] === 'student');
        $isParent  = ($user['role'] === 'parent');
        $active    = $activePage ?? '';

        function navItem(string $href, string $label, string $page, string $active, string $icon, int $badge=0): string {
            $base = 'flex items-center gap-3 px-4 py-2.5 rounded-[0.875rem] text-sm font-medium transition-all duration-150 mb-0.5';
            $cls = $page === $active
                ? $base . ' bg-brand-50 text-brand-700 font-semibold'
                : $base . ' text-gray-500 hover:bg-gray-50 hover:text-gray-900';
            $bdg = $badge > 0 ? "<span class='ml-auto bg-red-500 text-white text-[0.625rem] font-bold rounded-full px-1.5 min-w-[18px] text-center'>$badge</span>" : '';
            return "<a href='$href' class='$cls'>$icon $label $bdg</a>";
        }

        $ico = [
            'dash'    => '<svg class="w-[1.125rem] h-[1.125rem] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>',
            'grades'  => '<svg class="w-[1.125rem] h-[1.125rem] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>',
            'subject' => '<svg class="w-[1.125rem] h-[1.125rem] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>',
            'annc'    => '<svg class="w-[1.125rem] h-[1.125rem] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>',
            'users'   => '<svg class="w-[1.125rem] h-[1.125rem] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>',
            'school'  => '<svg class="w-[1.125rem] h-[1.125rem] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z"/></svg>',
            'report'  => '<svg class="w-[1.125rem] h-[1.125rem] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>',
        ];
        ?>

        <?= navItem('/native/pages/dashboard.php','Dashboard','dashboard',$active,$ico['dash']) ?>
        <?= navItem('/native/pages/announcements.php','Pengumuman','announcements',$active,$ico['annc']) ?>

        <?php if (!$isParent): ?>
        <?= navItem('/native/pages/grades.php','Nilai / Grades','grades',$active,$ico['grades']) ?>
        <?= navItem('/native/pages/subjects.php','Mata Pelajaran','subjects',$active,$ico['subject']) ?>
        <?= navItem('/native/pages/reports.php','Rapor','reports',$active,$ico['report']) ?>
        <?php endif; ?>

        <?php if ($isAdmin || $isTeacher): ?>
        <p class="text-[0.625rem] font-bold tracking-widest uppercase text-gray-400 px-4 pt-3 pb-2">Manajemen</p>
        <?= navItem('/native/pages/users.php','Kelola Users','users',$active,$ico['users']) ?>
        <?php endif; ?>

        <?php if ($isAdmin): ?>
        <p class="text-[0.625rem] font-bold tracking-widest uppercase text-gray-400 px-4 pt-3 pb-2">Sistem AcaHub</p>
        <?= navItem('/native/admin/schools.php','Sekolah Terdaftar','schools',$active,$ico['school']) ?>
        <?php endif; ?>
    </nav>

    <!-- User info -->
    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 font-bold text-sm flex items-center justify-center flex-shrink-0 uppercase">
                <?= strtoupper(substr($user['name'],0,1)) ?>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-gray-800 truncate"><?= e($user['name']) ?></div>
                <div class="text-xs text-gray-400 capitalize"><?= e($user['role']) ?></div>
            </div>
            <form method="POST" action="/native/auth/logout.php">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <button class="bg-transparent border-none cursor-pointer text-gray-400 hover:text-red-500 p-1 rounded-md transition-colors" title="Keluar">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Overlay mobile -->
<div class="sidebar-overlay fixed inset-0 bg-black/30 z-[39]" id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- MAIN WRAP -->
<div class="ml-0 lg:ml-64 min-h-screen">
    <!-- TOPBAR -->
    <header class="sticky top-0 z-30 h-16 bg-white/85 backdrop-blur-xl border-b border-gray-100 flex items-center gap-4 px-6">
        <button class="lg:hidden bg-transparent border-none cursor-pointer text-gray-600 flex" onclick="toggleSidebar()">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
        </button>
        <h2 class="flex-1 text-lg font-bold text-gray-800"><?= e($pageTitle ?? 'Dashboard') ?></h2>
        <div class="flex items-center gap-2">
            <a href="/native/pages/notifications.php" class="w-9 h-9 rounded-[0.625rem] flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-all relative" title="Notifikasi">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
            </a>
        </div>
    </header>

    <!-- FLASH -->
    <?php if ($flash): ?>
    <div class="mx-6 mt-4 px-4 py-3.5 rounded-[0.875rem] flex items-center gap-2.5 text-sm <?= $flash['type']==='success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' ?>">
        <?php if ($flash['type']==='success'): ?>
        <svg class="w-[1.125rem] h-[1.125rem] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
        <?php else: ?>
        <svg class="w-[1.125rem] h-[1.125rem] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
        <?php endif; ?>
        <?= e($flash['msg']) ?>
    </div>
    <?php endif; ?>

    <!-- PAGE CONTENT starts here -->
    <main class="p-6">
