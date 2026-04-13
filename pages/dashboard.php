<?php
//  1. SETUP 
$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
require_once '../layout/header.php';

//  2. AMBIL DATA ─
$pdo = getDB();

$totalStudents = $pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
$totalTeachers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='teacher'")->fetchColumn();
$totalSubjects = $pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();
$totalAnnc     = $pdo->query("SELECT COUNT(*) FROM announcements")->fetchColumn();

if ($isAdmin) {
    $gradeRows = $pdo->query("SELECT score FROM grades")->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT score FROM grades WHERE student_id = ?");
    $stmt->execute([$user['id']]);
    $gradeRows = $stmt->fetchAll();
}

$dist = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0];
foreach ($gradeRows as $row) {
    $s = (float)$row['score'];
    if ($s >= 90)     $dist['A']++;
    elseif ($s >= 75) $dist['B']++;
    elseif ($s >= 60) $dist['C']++;
    elseif ($s >= 50) $dist['D']++;
    else              $dist['E']++;
}

$subjAvg = $pdo->query("
    SELECT s.name, COALESCE(AVG(g.score), 0) AS avg
    FROM subjects s
    LEFT JOIN grades g ON g.subject_id = s.id
    GROUP BY s.id, s.name
    LIMIT 6
")->fetchAll();

$recentAnnc = $pdo->query("
    SELECT a.*, u.name AS author_name
    FROM announcements a
    JOIN users u ON u.id = a.author_id
    ORDER BY a.created_at DESC
    LIMIT 3
")->fetchAll();
?>

<!--  3. HTML OUTPUT  -->
<div class="mb-6">
    <h1 class="text-2xl font-black text-gray-900">Selamat datang, <?= e($user['name']) ?>! 👋</h1>
    <p class="text-sm text-gray-500 mt-1">
        <?php if ($isAdmin): ?>Panel administrasi AcaHub
        <?php elseif ($isTeacher): ?>Ringkasan mengajar Anda
        <?php else: ?>Ringkasan akademik Anda
        <?php endif; ?>
    </p>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-[1.25rem] px-5 py-4 border border-gray-100 flex items-center gap-4 hover:shadow-[0_8px_28px_rgba(0,0,0,0.07)] hover:-translate-y-0.5 transition-all">
        <div class="w-12 h-12 rounded-[0.875rem] bg-brand-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-[1.375rem] h-[1.375rem] stroke-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
        </div>
        <div><div class="text-[1.625rem] font-black text-gray-900 leading-none"><?= $totalStudents ?></div><div class="text-xs text-gray-400 mt-1">Total Siswa</div></div>
    </div>
    <div class="bg-white rounded-[1.25rem] px-5 py-4 border border-gray-100 flex items-center gap-4 hover:shadow-[0_8px_28px_rgba(0,0,0,0.07)] hover:-translate-y-0.5 transition-all">
        <div class="w-12 h-12 rounded-[0.875rem] bg-accent-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-[1.375rem] h-[1.375rem] stroke-accent-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
        </div>
        <div><div class="text-[1.625rem] font-black text-gray-900 leading-none"><?= $totalTeachers ?></div><div class="text-xs text-gray-400 mt-1">Total Guru</div></div>
    </div>
    <div class="bg-white rounded-[1.25rem] px-5 py-4 border border-gray-100 flex items-center gap-4 hover:shadow-[0_8px_28px_rgba(0,0,0,0.07)] hover:-translate-y-0.5 transition-all">
        <div class="w-12 h-12 rounded-[0.875rem] bg-green-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-[1.375rem] h-[1.375rem] stroke-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
        </div>
        <div><div class="text-[1.625rem] font-black text-gray-900 leading-none"><?= $totalSubjects ?></div><div class="text-xs text-gray-400 mt-1">Mata Pelajaran</div></div>
    </div>
    <div class="bg-white rounded-[1.25rem] px-5 py-4 border border-gray-100 flex items-center gap-4 hover:shadow-[0_8px_28px_rgba(0,0,0,0.07)] hover:-translate-y-0.5 transition-all">
        <div class="w-12 h-12 rounded-[0.875rem] bg-purple-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-[1.375rem] h-[1.375rem] stroke-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
        </div>
        <div><div class="text-[1.625rem] font-black text-gray-900 leading-none"><?= $totalAnnc ?></div><div class="text-xs text-gray-400 mt-1">Pengumuman</div></div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-[1.25rem] p-6 border border-gray-100">
        <h3 class="text-sm font-bold text-gray-700 mb-5">📊 Distribusi Nilai</h3>
        <div class="relative h-[220px]"><canvas id="distChart"></canvas></div>
    </div>
    <div class="bg-white rounded-[1.25rem] p-6 border border-gray-100">
        <h3 class="text-sm font-bold text-gray-700 mb-5">📈 Rata-rata per Mapel</h3>
        <div class="relative h-[220px]"><canvas id="avgChart"></canvas></div>
    </div>
</div>

<div class="bg-white rounded-[1.25rem] p-6 border border-gray-100 mb-6">
    <h3 class="text-sm font-bold text-gray-700 mb-4">⚡ Aksi Cepat</h3>
    <div class="flex flex-wrap gap-3">
        <a href="grades.php" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[0.825rem] font-medium border-[1.5px] border-gray-200 text-gray-600 bg-white hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700 transition-all">Lihat Nilai</a>
        <a href="subjects.php" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[0.825rem] font-medium border-[1.5px] border-gray-200 text-gray-600 bg-white hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700 transition-all">Mata Pelajaran</a>
        <a href="announcements.php" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[0.825rem] font-medium border-[1.5px] border-gray-200 text-gray-600 bg-white hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700 transition-all">Pengumuman</a>
        <?php if ($isAdmin || $isTeacher): ?>
        <a href="users.php" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[0.825rem] font-medium border-[1.5px] border-gray-200 text-gray-600 bg-white hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700 transition-all">Kelola Users</a>
        <a href="grades.php?action=create" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[0.825rem] font-medium bg-accent-500 text-white border-[1.5px] border-accent-500 hover:bg-accent-600 transition-all">+ Input Nilai Baru</a>
        <?php endif; ?>
    </div>
</div>

<div class="bg-white rounded-[1.25rem] p-6 border border-gray-100">
    <h3 class="text-sm font-bold text-gray-700 mb-4">📢 Pengumuman Terbaru</h3>
    <div class="flex flex-col gap-3">
        <?php if ($recentAnnc): ?>
            <?php foreach ($recentAnnc as $a): ?>
            <div class="bg-white rounded-2xl px-5 py-4 border border-gray-100 flex gap-4 items-start">
                <div class="w-2 h-2 rounded-full bg-brand-500 mt-1.5 flex-shrink-0"></div>
                <div>
                    <div class="text-sm font-semibold text-gray-800"><?= e($a['title']) ?></div>
                    <div class="text-xs text-gray-400 mt-1">oleh <?= e($a['author_name']) ?> · <?= date('d M Y', strtotime($a['created_at'])) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-400 text-sm">Belum ada pengumuman.</p>
        <?php endif; ?>
    </div>
</div>

<script>
const distCtx = document.getElementById('distChart').getContext('2d');
new Chart(distCtx, {
    type: 'doughnut',
    data: {
        labels: ['A (≥90)', 'B (75-89)', 'C (60-74)', 'D (50-59)', 'E (<50)'],
        datasets: [{ data: <?= json_encode(array_values($dist)) ?>, backgroundColor: ['#10b981','#0891b2','#f59e0b','#f97316','#ef4444'], borderWidth: 0, borderRadius: 4 }]
    },
    options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { padding: 14, usePointStyle: true, pointStyleWidth: 8, font: { size: 11, family: 'Inter' } } } } }
});

const avgCtx  = document.getElementById('avgChart').getContext('2d');
const subjData = <?= json_encode(array_values($subjAvg)) ?>;
new Chart(avgCtx, {
    type: 'bar',
    data: {
        labels: subjData.map(s => s.name),
        datasets: [{ label: 'Rata-rata', data: subjData.map(s => parseFloat(s.avg).toFixed(1)), backgroundColor: subjData.map(s => s.avg >= 75 ? '#0891b2' : '#f97316'), borderRadius: 8, borderSkipped: false, barThickness: 28 }]
    },
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100, ticks: { font: { size: 11, family: 'Inter' } }, grid: { color: '#f3f4f6' } }, x: { ticks: { font: { size: 10, family: 'Inter' } }, grid: { display: false } } }, plugins: { legend: { display: false } } }
});
</script>

<?php require_once '../layout/footer.php'; ?>
