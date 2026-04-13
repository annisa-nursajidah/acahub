<?php
//1. SETU
$pageTitle  = 'Nilai / Grades';
$activePage = 'grades';

// Load config dulu (tanpa output) untuk handle POST sebelum header
require_once __DIR__ . '/../config.php';
requireLogin();
$user = currentUser();
$pdo  = getDB();

$isAdmin   = ($user['role'] === 'admin');
$isTeacher = ($user['role'] === 'teacher');

// 2. HANDLE FORM (POST) 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($isAdmin || $isTeacher)) {
    verifyCsrf();
    $student_id = (int)($_POST['student_id'] ?? 0);
    $subject_id = (int)($_POST['subject_id'] ?? 0);
    $score      = (float)($_POST['score']      ?? 0);
    $type       = $_POST['type']     ?? 'UH';
    $semester   = (int)($_POST['semester'] ?? 1);
    $notes      = trim($_POST['notes']     ?? '');

    if ($student_id && $subject_id && $score > 0) {
        $pdo->prepare("INSERT INTO grades (student_id, subject_id, score, type, semester, notes) VALUES (?, ?, ?, ?, ?, ?)")
            ->execute([$student_id, $subject_id, $score, $type, $semester, $notes]);
        setFlash('success', 'Nilai berhasil disimpan!');
        redirect('/native/pages/grades.php');
    }
}

// Baru include header (output HTML)
require_once '../layout/header.php';

//3. AMBIL DATA 
if ($isAdmin || $isTeacher) {
    $grades = $pdo->query("
        SELECT g.*, u.name AS student_name, s.name AS subject_name
        FROM grades g
        JOIN users u ON u.id = g.student_id
        JOIN subjects s ON s.id = g.subject_id
        ORDER BY g.created_at DESC
    ")->fetchAll();
} else {
    $stmt = $pdo->prepare("
        SELECT g.*, u.name AS student_name, s.name AS subject_name
        FROM grades g
        JOIN users u ON u.id = g.student_id
        JOIN subjects s ON s.id = g.subject_id
        WHERE g.student_id = ?
        ORDER BY g.created_at DESC
    ");
    $stmt->execute([$user['id']]);
    $grades = $stmt->fetchAll();
}

$students = $pdo->query("SELECT id, name FROM users WHERE role = 'student' ORDER BY name")->fetchAll();
$subjects = $pdo->query("SELECT id, name FROM subjects ORDER BY name")->fetchAll();

$avg     = $grades ? array_sum(array_column($grades, 'score')) / count($grades) : 0;
$highest = $grades ? max(array_column($grades, 'score')) : 0;
$lowest  = $grades ? min(array_column($grades, 'score')) : 0;

$gradeLetter = function(float $score): string {
    if ($score >= 90) return 'A';
    if ($score >= 75) return 'B';
    if ($score >= 60) return 'C';
    if ($score >= 50) return 'D';
    return 'E';
};

$badgeColor = [
    'A' => 'bg-green-50 text-green-700',
    'B' => 'bg-brand-50 text-brand-700',
    'C' => 'bg-yellow-50 text-yellow-700',
    'D' => 'bg-orange-50 text-orange-700',
    'E' => 'bg-red-50 text-red-700',
];
?>

<!--  4. HTML OUTPUT  -->
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-extrabold">📊 Nilai / Grades</h1>
    <?php if ($isAdmin || $isTeacher): ?>
    <button class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-[0.875rem] text-[0.85rem] font-semibold bg-accent-500 text-white border-none cursor-pointer font-inter hover:bg-accent-600 transition-all"
            onclick="document.getElementById('form-nilai').style.display = document.getElementById('form-nilai').style.display === 'none' ? 'block' : 'none'">
        + Input Nilai
    </button>
    <?php endif; ?>
</div>

<?php if ($grades): ?>
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <div class="text-[1.75rem] font-black text-brand-600"><?= number_format($avg, 1) ?></div>
        <div class="text-xs text-gray-400 mt-1">Rata-rata</div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <div class="text-[1.75rem] font-black text-green-600"><?= number_format($highest, 1) ?></div>
        <div class="text-xs text-gray-400 mt-1">Nilai Tertinggi</div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
        <div class="text-[1.75rem] font-black <?= $lowest >= 75 ? 'text-green-600' : 'text-red-600' ?>"><?= number_format($lowest, 1) ?></div>
        <div class="text-xs text-gray-400 mt-1">Nilai Terendah</div>
    </div>
</div>
<?php endif; ?>

<?php if ($isAdmin || $isTeacher): ?>
<div id="form-nilai" style="display:none">
    <div class="bg-white rounded-[1.25rem] border border-gray-100 p-6 mb-6">
        <h3 class="text-[0.925rem] font-bold text-gray-700 mb-4">+ Input Nilai Baru</h3>
        <form method="POST" action="grades.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[0.8rem] font-medium text-gray-600">Siswa</label>
                    <select name="student_id" required class="px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500">
                        <option value="">Pilih siswa...</option>
                        <?php foreach ($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= e($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[0.8rem] font-medium text-gray-600">Mata Pelajaran</label>
                    <select name="subject_id" required class="px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500">
                        <option value="">Pilih mapel...</option>
                        <?php foreach ($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= e($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[0.8rem] font-medium text-gray-600">Nilai (0-100)</label>
                    <input type="number" name="score" min="0" max="100" step="0.5" placeholder="85" required class="px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[0.8rem] font-medium text-gray-600">Tipe</label>
                    <select name="type" class="px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500">
                        <option value="UH">Ulangan Harian</option>
                        <option value="UTS">UTS</option>
                        <option value="UAS">UAS</option>
                        <option value="Tugas">Tugas</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[0.8rem] font-medium text-gray-600">Semester</label>
                    <select name="semester" class="px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500">
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[0.8rem] font-medium text-gray-600">Catatan (opsional)</label>
                    <input type="text" name="notes" placeholder="Catatan tambahan..." class="px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500">
                </div>
            </div>
            <button type="submit" class="mt-5 px-7 py-3 rounded-xl text-sm font-bold bg-accent-500 text-white border-none cursor-pointer font-inter hover:bg-accent-600 transition-all">Simpan Nilai</button>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="bg-white rounded-[1.25rem] border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-[0.925rem] font-bold text-gray-800">Daftar Nilai</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <?php if ($isAdmin || $isTeacher): ?><th class="text-xs font-semibold text-gray-400 uppercase px-5 py-3 border-b border-gray-100 text-left bg-gray-50">Siswa</th><?php endif; ?>
                    <th class="text-xs font-semibold text-gray-400 uppercase px-5 py-3 border-b border-gray-100 text-left bg-gray-50">Mata Pelajaran</th>
                    <th class="text-xs font-semibold text-gray-400 uppercase px-5 py-3 border-b border-gray-100 text-left bg-gray-50">Nilai</th>
                    <th class="text-xs font-semibold text-gray-400 uppercase px-5 py-3 border-b border-gray-100 text-left bg-gray-50">Tipe</th>
                    <th class="text-xs font-semibold text-gray-400 uppercase px-5 py-3 border-b border-gray-100 text-left bg-gray-50">Semester</th>
                    <th class="text-xs font-semibold text-gray-400 uppercase px-5 py-3 border-b border-gray-100 text-left bg-gray-50">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($grades): ?>
                    <?php foreach ($grades as $g):
                        $sc  = (float)$g['score'];
                        $ltr = $gradeLetter($sc);
                        $cls = $badgeColor[$ltr];
                    ?>
                    <tr class="hover:bg-gray-50">
                        <?php if ($isAdmin || $isTeacher): ?>
                        <td class="px-5 py-3.5 text-sm text-gray-700 border-b border-gray-50">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-brand-100 text-brand-700 text-[0.7rem] font-bold flex items-center justify-center"><?= strtoupper(substr($g['student_name'], 0, 1)) ?></div>
                                <span><?= e($g['student_name']) ?></span>
                            </div>
                        </td>
                        <?php endif; ?>
                        <td class="px-5 py-3.5 text-sm text-gray-700 border-b border-gray-50"><?= e($g['subject_name']) ?></td>
                        <td class="px-5 py-3.5 text-sm border-b border-gray-50"><span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold <?= $cls ?>"><?= number_format($sc, 1) ?> (<?= $ltr ?>)</span></td>
                        <td class="px-5 py-3.5 text-sm text-gray-500 border-b border-gray-50"><?= e($g['type']) ?></td>
                        <td class="px-5 py-3.5 text-sm text-gray-500 border-b border-gray-50">Sem <?= $g['semester'] ?></td>
                        <td class="px-5 py-3.5 text-sm text-gray-400 border-b border-gray-50"><?= date('d M Y', strtotime($g['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-12 text-gray-400">Belum ada data nilai.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../layout/footer.php'; ?>
