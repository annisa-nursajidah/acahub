<?php
//  1. SETUP 
$pageTitle  = 'Rapor';
$activePage = 'reports';
require_once '../layout/header.php';

$pdo = getDB();

//  2. AMBIL DATA 
$myGrades = [];
$avg      = 0;

if ($isStudent) {
    $stmt = $pdo->prepare("
        SELECT g.*, s.name AS subject_name, s.code
        FROM grades g
        JOIN subjects s ON s.id = g.subject_id
        WHERE g.student_id = ? AND g.semester = 1
        ORDER BY s.name
    ");
    $stmt->execute([$user['id']]);
    $myGrades = $stmt->fetchAll();
    $avg = $myGrades ? array_sum(array_column($myGrades, 'score')) / count($myGrades) : 0;
}

$scoreColors = ['A' => 'text-green-600', 'B' => 'text-brand-600', 'C' => 'text-yellow-700', 'D' => 'text-orange-700', 'E' => 'text-red-700'];
?>

<!--  3. HTML OUTPUT  -->
<h1 class="text-xl font-extrabold mb-6">📄 Rapor Akademik</h1>

<?php if ($isStudent): ?>
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-[1.25rem] border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-br from-brand-600 to-brand-800 px-8 pt-8 pb-6 text-white">
            <div class="text-lg font-black tracking-tight">AcaHub — Rapor Siswa</div>
            <div class="mt-4 grid grid-cols-2 gap-2 text-[0.85rem]">
                <span class="text-white/65">Nama</span>     <span class="font-semibold"><?= e($user['name']) ?></span>
                <span class="text-white/65">Email</span>    <span class="font-semibold"><?= e($user['email']) ?></span>
                <span class="text-white/65">Semester</span> <span class="font-semibold">1 / 2025-2026</span>
                <span class="text-white/65">Peran</span>    <span class="font-semibold">Siswa</span>
            </div>
        </div>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="text-xs font-semibold text-gray-400 uppercase px-6 py-3 border-b border-gray-100 text-left bg-gray-50">Mata Pelajaran</th>
                    <th class="text-xs font-semibold text-gray-400 uppercase px-6 py-3 border-b border-gray-100 text-left bg-gray-50">Nilai</th>
                    <th class="text-xs font-semibold text-gray-400 uppercase px-6 py-3 border-b border-gray-100 text-left bg-gray-50">Predikat</th>
                    <th class="text-xs font-semibold text-gray-400 uppercase px-6 py-3 border-b border-gray-100 text-left bg-gray-50">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($myGrades): ?>
                    <?php foreach ($myGrades as $g):
                        $sc  = (float)$g['score'];
                        $ltr = $sc >= 90 ? 'A' : ($sc >= 75 ? 'B' : ($sc >= 60 ? 'C' : ($sc >= 50 ? 'D' : 'E')));
                        $ket = $sc >= 75 ? 'Tuntas' : 'Belum Tuntas';
                        $cls = $scoreColors[$ltr];
                    ?>
                    <tr>
                        <td class="px-6 py-3.5 text-sm border-b border-gray-50"><?= e($g['subject_name']) ?></td>
                        <td class="px-6 py-3.5 text-sm font-bold border-b border-gray-50 <?= $cls ?>"><?= number_format($sc, 1) ?></td>
                        <td class="px-6 py-3.5 text-sm font-bold border-b border-gray-50 <?= $cls ?>"><?= $ltr ?></td>
                        <td class="px-6 py-3.5 text-[0.8rem] font-medium border-b border-gray-50 <?= $sc >= 75 ? 'text-green-600' : 'text-red-700' ?>"><?= $ket ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center py-8 text-gray-400">Belum ada nilai.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-6 py-6 border-t-2 border-gray-100 flex justify-between items-center">
            <div class="text-sm text-gray-500">Dicetak: <?= date('d M Y') ?></div>
            <div>
                <span class="text-sm text-gray-500 mr-3">Rata-rata:</span>
                <span class="bg-brand-50 text-brand-700 px-5 py-2.5 rounded-xl font-extrabold text-lg"><?= number_format($avg, 1) ?></span>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="text-center py-16 text-gray-400">
    <div class="text-5xl">📋</div>
    <p class="mt-4">Fitur rapor hanya tersedia untuk siswa.</p>
    <a href="grades.php" class="inline-block mt-4 px-6 py-2.5 rounded-xl bg-brand-500 text-white font-semibold text-sm hover:bg-brand-600 transition-all">Lihat Data Nilai</a>
</div>
<?php endif; ?>

<?php require_once '../layout/footer.php'; ?>
