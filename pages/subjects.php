<?php
$pageTitle  = 'Mata Pelajaran';
$activePage = 'subjects';
require_once '../layout/header.php';
$pdo = getDB();

$subjects = $pdo->query("
    SELECT s.*, u.name as teacher_name
    FROM subjects s
    LEFT JOIN users u ON u.id=s.teacher_id
    ORDER BY s.name
")->fetchAll();
?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-extrabold">📚 Mata Pelajaran</h1>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php if($subjects): ?>
    <?php foreach($subjects as $s): ?>
    <div class="bg-white rounded-[1.25rem] p-6 border border-gray-100 hover:shadow-[0_8px_28px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-brand-500 to-accent-500 rounded-t-[1.25rem]"></div>
        <div class="inline-block px-2.5 py-0.5 rounded-md text-[0.7rem] font-bold bg-brand-50 text-brand-700 mb-3"><?= e($s['code'] ?: 'MAP') ?></div>
        <div class="text-base font-extrabold text-gray-900 mb-2"><?= e($s['name']) ?></div>
        <div class="text-[0.8rem] text-gray-500 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/></svg>
            <?= e($s['teacher_name'] ?? 'Belum ditentukan') ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p class="text-gray-400 col-span-full text-center py-12">Belum ada mata pelajaran.</p>
    <?php endif; ?>
</div>

<?php require_once '../layout/footer.php'; ?>
