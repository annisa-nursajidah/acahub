<?php
//  1. SETUP 
$pageTitle  = 'Pengumuman';
$activePage = 'announcements';

// Load config dulu (tanpa output) untuk handle POST sebelum header
require_once __DIR__ . '/../config.php';
requireLogin();
$user = currentUser();
$pdo  = getDB();

$isAdmin   = ($user['role'] === 'admin');
$isTeacher = ($user['role'] === 'teacher');

//  2. HANDLE FORM (POST) — sebelum output HTML 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($isAdmin || $isTeacher)) {
    verifyCsrf();
    $title = trim($_POST['title'] ?? '');
    $body  = trim($_POST['body']  ?? '');

    if ($title && $body) {
        $pdo->prepare("INSERT INTO announcements (title, body, author_id) VALUES (?, ?, ?)")
            ->execute([$title, $body, $user['id']]);
        setFlash('success', 'Pengumuman berhasil dikirim!');
        redirect('/native/pages/announcements.php');
    }
}

// Baru include header (output HTML)
require_once '../layout/header.php';

//  3. AMBIL DATA 
$announcements = $pdo->query("
    SELECT a.*, u.name AS author_name
    FROM announcements a
    JOIN users u ON u.id = a.author_id
    ORDER BY a.created_at DESC
")->fetchAll();
?>

<!--  4. HTML OUTPUT  -->
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-extrabold text-gray-900">📢 Pengumuman</h1>
    <?php if ($isAdmin || $isTeacher): ?>
    <button class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-[0.875rem] text-[0.85rem] font-semibold bg-accent-500 text-white border-none cursor-pointer font-inter hover:bg-accent-600 transition-all"
            onclick="document.getElementById('form-annc').style.display = document.getElementById('form-annc').style.display === 'none' ? 'block' : 'none'">
        + Buat Pengumuman
    </button>
    <?php endif; ?>
</div>

<?php if ($isAdmin || $isTeacher): ?>
<div id="form-annc" style="display:none;">
    <div class="bg-white rounded-[1.25rem] border border-gray-100 p-6 mb-6">
        <h3 class="text-[0.925rem] font-bold text-gray-700 mb-4">Buat Pengumuman Baru</h3>
        <form method="POST" action="announcements.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="mb-4">
                <label class="block text-[0.825rem] font-medium text-gray-600 mb-1.5">Judul</label>
                <input type="text" name="title" placeholder="Judul pengumuman" required class="w-full px-4 py-3 rounded-xl font-inter border-[1.5px] border-gray-200 text-sm text-gray-900 outline-none focus:border-brand-500 transition-all">
            </div>
            <div class="mb-4">
                <label class="block text-[0.825rem] font-medium text-gray-600 mb-1.5">Isi</label>
                <textarea name="body" rows="5" placeholder="Tulis isi pengumuman..." required class="w-full px-4 py-3 rounded-xl font-inter border-[1.5px] border-gray-200 text-sm text-gray-900 outline-none focus:border-brand-500 resize-y transition-all"></textarea>
            </div>
            <button type="submit" class="px-7 py-3 rounded-xl text-sm font-bold bg-accent-500 text-white border-none cursor-pointer font-inter hover:bg-accent-600 transition-all">Kirim</button>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="flex flex-col gap-4">
    <?php if ($announcements): ?>
        <?php foreach ($announcements as $a): ?>
        <div class="bg-white rounded-[1.25rem] p-6 border border-gray-100 hover:shadow-[0_4px_20px_rgba(0,0,0,0.07)] transition-shadow">
            <div class="flex items-start justify-between gap-4">
                <div class="text-base font-bold text-gray-900"><?= e($a['title']) ?></div>
                <div class="text-xs text-gray-400 whitespace-nowrap"><?= date('d M Y', strtotime($a['created_at'])) ?></div>
            </div>
            <p class="text-sm text-gray-600 mt-3 leading-relaxed"><?= nl2br(e($a['body'])) ?></p>
            <div class="mt-4 flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-brand-100 text-brand-700 text-[0.7rem] font-bold flex items-center justify-center uppercase"><?= strtoupper(substr($a['author_name'], 0, 1)) ?></div>
                <div class="text-[0.8rem] font-medium text-gray-500"><?= e($a['author_name']) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-16 text-gray-400">
            <div class="text-5xl mb-4">📭</div>
            <p>Belum ada pengumuman.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../layout/footer.php'; ?>
