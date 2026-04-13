<?php
// ── 1. SETUP ──────────────────────────────────────
$pageTitle  = 'Panel Admin';
$activePage = 'admin';

// Load config dulu (tanpa output) untuk handle POST sebelum header
require_once __DIR__ . '/../config.php';
requireLogin();
$user = currentUser();
if (!$user || $user['role'] !== 'admin') {
    redirect('/native/pages/dashboard.php');
}

$pdo = getDB();
$tab = $_GET['tab'] ?? 'users';

$isAdmin   = true;
$isTeacher = false;

// ── 2. HANDLE FORM (POST) — sebelum output HTML ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete_user') {
        $uid = (int)$_POST['uid'];
        if ($uid === $user['id']) {
            setFlash('error', 'Tidak bisa menghapus akun sendiri.');
        } else {
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$uid]);
            setFlash('success', 'User berhasil dihapus.');
        }
    }

    if ($action === 'create_user') {
        $name  = trim($_POST['name']     ?? '');
        $email = trim($_POST['email']    ?? '');
        $role  = $_POST['role']          ?? 'student';
        $pass  = $_POST['password']      ?? 'password123';

        if ($name && $email) {
            $exists = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $exists->execute([$email]);
            if ($exists->fetch()) {
                setFlash('error', 'Email sudah terdaftar.');
            } else {
                $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)")
                    ->execute([$name, $email, password_hash($pass, PASSWORD_BCRYPT), $role]);
                setFlash('success', 'User berhasil dibuat.');
            }
        } else {
            setFlash('error', 'Nama dan email wajib diisi.');
        }
    }

    if ($action === 'create_annc') {
        $title = trim($_POST['title'] ?? '');
        $body  = trim($_POST['body']  ?? '');
        if ($title && $body) {
            $pdo->prepare("INSERT INTO announcements (title, body, author_id) VALUES (?, ?, ?)")
                ->execute([$title, $body, $user['id']]);
            setFlash('success', 'Pengumuman berhasil dikirim.');
        } else {
            setFlash('error', 'Judul dan isi wajib diisi.');
        }
    }

    if ($action === 'delete_annc') {
        $pdo->prepare("DELETE FROM announcements WHERE id = ?")->execute([(int)$_POST['aid']]);
        setFlash('success', 'Pengumuman dihapus.');
    }

    header("Location: admin.php?tab=$tab");
    exit;
}

// Baru include header (output HTML)
require_once '../layout/header.php';

// ── 3. AMBIL DATA ─────────────────────────────────
$stats = [
    'users'    => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'students' => $pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn(),
    'teachers' => $pdo->query("SELECT COUNT(*) FROM users WHERE role='teacher'")->fetchColumn(),
    'subjects' => $pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn(),
    'grades'   => $pdo->query("SELECT COUNT(*) FROM grades")->fetchColumn(),
    'annc'     => $pdo->query("SELECT COUNT(*) FROM announcements")->fetchColumn(),
];

$users    = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
$anncs    = $pdo->query("SELECT a.*, u.name AS author_name FROM announcements a JOIN users u ON u.id = a.author_id ORDER BY a.created_at DESC")->fetchAll();
$subjects = $pdo->query("SELECT s.*, u.name AS teacher_name FROM subjects s LEFT JOIN users u ON u.id = s.teacher_id ORDER BY s.name")->fetchAll();

$roleBadge = ['admin' => 'bg-yellow-100 text-yellow-800', 'teacher' => 'bg-brand-50 text-brand-700', 'student' => 'bg-green-50 text-green-700', 'parent' => 'bg-purple-50 text-purple-700'];
$thClass   = 'text-xs font-semibold text-gray-400 uppercase tracking-wider px-6 py-3 border-b border-gray-100 text-left bg-gray-50';
$tdClass   = 'px-6 py-3.5 text-sm text-gray-700 border-b border-gray-50';
$inputClass = 'px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500 focus:ring-[3px] focus:ring-brand-500/10';
?>

<!-- ── 4. HTML OUTPUT ──────────────────────────────── -->
<div class="grid grid-cols-3 sm:grid-cols-6 gap-4 mb-6">
    <?php foreach ([['Total User','users','👤'],['Siswa','students','🎓'],['Guru','teachers','📚'],['Mapel','subjects','📖'],['Data Nilai','grades','📊'],['Pengumuman','annc','📢']] as [$lbl,$key,$ico]): ?>
    <div class="bg-white rounded-2xl p-4 text-center border border-gray-100">
        <div class="text-2xl"><?= $ico ?></div>
        <div class="text-2xl font-black text-brand-600"><?= $stats[$key] ?></div>
        <div class="text-[0.7rem] text-gray-400 mt-0.5"><?= $lbl ?></div>
    </div>
    <?php endforeach; ?>
</div>

<div class="flex gap-1 bg-gray-100 rounded-2xl p-1 mb-6 flex-wrap">
    <a href="?tab=users"         class="flex-1 min-w-[80px] py-2.5 px-4 rounded-xl text-center text-[0.825rem] font-semibold transition-all <?= $tab === 'users'         ? 'bg-white text-brand-700 shadow-[0_2px_8px_rgba(0,0,0,0.08)]' : 'text-gray-500 hover:bg-white' ?>">👤 Users</a>
    <a href="?tab=announcements" class="flex-1 min-w-[80px] py-2.5 px-4 rounded-xl text-center text-[0.825rem] font-semibold transition-all <?= $tab === 'announcements' ? 'bg-white text-brand-700 shadow-[0_2px_8px_rgba(0,0,0,0.08)]' : 'text-gray-500 hover:bg-white' ?>">📢 Pengumuman</a>
    <a href="?tab=subjects"      class="flex-1 min-w-[80px] py-2.5 px-4 rounded-xl text-center text-[0.825rem] font-semibold transition-all <?= $tab === 'subjects'      ? 'bg-white text-brand-700 shadow-[0_2px_8px_rgba(0,0,0,0.08)]' : 'text-gray-500 hover:bg-white' ?>">📚 Mata Pelajaran</a>
</div>

<?php if ($tab === 'users'): ?>
<div class="bg-white rounded-[1.25rem] border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-[0.925rem] font-bold text-gray-800">Kelola Pengguna</h3>
        <button onclick="document.getElementById('form-user').style.display = document.getElementById('form-user').style.display === 'none' ? 'block' : 'none'"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[0.8rem] font-semibold bg-accent-500 text-white border-none cursor-pointer hover:bg-accent-600 transition-all">
            + Tambah User
        </button>
    </div>
    <div id="form-user" style="display:none;" class="bg-gray-50 border-t border-gray-100 p-6">
        <form method="POST" action="?tab=users">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="create_user">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5"><label class="text-[0.8rem] font-medium text-gray-600">Nama</label><input type="text" name="name" required class="<?= $inputClass ?>"></div>
                <div class="flex flex-col gap-1.5"><label class="text-[0.8rem] font-medium text-gray-600">Email</label><input type="email" name="email" required class="<?= $inputClass ?>"></div>
                <div class="flex flex-col gap-1.5"><label class="text-[0.8rem] font-medium text-gray-600">Peran</label>
                    <select name="role" class="<?= $inputClass ?>"><option value="student">Siswa</option><option value="teacher">Guru</option><option value="parent">Orang Tua</option><option value="admin">Admin</option></select>
                </div>
                <div class="flex flex-col gap-1.5"><label class="text-[0.8rem] font-medium text-gray-600">Password</label><input type="password" name="password" value="password123" class="<?= $inputClass ?>"></div>
            </div>
            <button type="submit" class="mt-5 px-6 py-2.5 rounded-xl text-sm font-semibold bg-accent-500 text-white border-none cursor-pointer hover:bg-accent-600 transition-all">Simpan User</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead><tr><th class="<?= $thClass ?>">#</th><th class="<?= $thClass ?>">Nama</th><th class="<?= $thClass ?>">Email</th><th class="<?= $thClass ?>">Peran</th><th class="<?= $thClass ?>">Bergabung</th><th class="<?= $thClass ?>">Aksi</th></tr></thead>
            <tbody>
                <?php if ($users): ?>
                    <?php foreach ($users as $i => $u): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="<?= $tdClass ?> text-gray-400"><?= $i + 1 ?></td>
                        <td class="<?= $tdClass ?>">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 text-xs font-bold flex items-center justify-center"><?= strtoupper(substr($u['name'], 0, 1)) ?></div>
                                <span class="font-semibold"><?= e($u['name']) ?></span>
                            </div>
                        </td>
                        <td class="<?= $tdClass ?> text-gray-500"><?= e($u['email']) ?></td>
                        <td class="<?= $tdClass ?>"><span class="inline-block px-2.5 py-0.5 rounded-full text-[0.7rem] font-bold capitalize <?= $roleBadge[$u['role']] ?? '' ?>"><?= e($u['role']) ?></span></td>
                        <td class="<?= $tdClass ?> text-gray-400"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                        <td class="<?= $tdClass ?>">
                            <?php if ($u['id'] !== $user['id']): ?>
                            <form method="POST" action="?tab=users" class="inline" onsubmit="return confirm('Hapus user ini?')">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="uid" value="<?= $u['id'] ?>">
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-700 border border-red-200 cursor-pointer hover:bg-red-500 hover:text-white transition-all">Hapus</button>
                            </form>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">Anda</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-12 text-gray-400 text-sm">Tidak ada data user.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php elseif ($tab === 'announcements'): ?>
<div class="bg-white rounded-[1.25rem] border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-[0.925rem] font-bold text-gray-800">Kelola Pengumuman</h3>
        <button onclick="document.getElementById('form-annc').style.display = document.getElementById('form-annc').style.display === 'none' ? 'block' : 'none'"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[0.8rem] font-semibold bg-accent-500 text-white border-none cursor-pointer hover:bg-accent-600 transition-all">
            + Buat Pengumuman
        </button>
    </div>
    <div id="form-annc" style="display:none;" class="bg-gray-50 border-t border-gray-100 p-6">
        <form method="POST" action="?tab=announcements">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="create_annc">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-1.5"><label class="text-[0.8rem] font-medium text-gray-600">Judul</label><input type="text" name="title" required class="<?= $inputClass ?>"></div>
                <div class="flex flex-col gap-1.5"><label class="text-[0.8rem] font-medium text-gray-600">Isi</label><textarea name="body" rows="4" required class="<?= $inputClass ?> resize-y"></textarea></div>
            </div>
            <button type="submit" class="mt-5 px-6 py-2.5 rounded-xl text-sm font-semibold bg-accent-500 text-white border-none cursor-pointer hover:bg-accent-600 transition-all">Kirim</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead><tr><th class="<?= $thClass ?>">#</th><th class="<?= $thClass ?>">Judul</th><th class="<?= $thClass ?>">Penulis</th><th class="<?= $thClass ?>">Tanggal</th><th class="<?= $thClass ?>">Aksi</th></tr></thead>
            <tbody>
                <?php if ($anncs): ?>
                    <?php foreach ($anncs as $i => $a): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="<?= $tdClass ?> text-gray-400"><?= $i + 1 ?></td>
                        <td class="<?= $tdClass ?>">
                            <div class="font-semibold text-gray-800"><?= e($a['title']) ?></div>
                            <div class="text-xs text-gray-400 mt-0.5"><?= e(mb_strimwidth($a['body'], 0, 60, '…')) ?></div>
                        </td>
                        <td class="<?= $tdClass ?> text-gray-500"><?= e($a['author_name']) ?></td>
                        <td class="<?= $tdClass ?> text-gray-400"><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                        <td class="<?= $tdClass ?>">
                            <form method="POST" action="?tab=announcements" class="inline" onsubmit="return confirm('Hapus pengumuman ini?')">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="action" value="delete_annc">
                                <input type="hidden" name="aid" value="<?= $a['id'] ?>">
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-700 border border-red-200 cursor-pointer hover:bg-red-500 hover:text-white transition-all">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-12 text-gray-400 text-sm">Belum ada pengumuman.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php elseif ($tab === 'subjects'): ?>
<div class="bg-white rounded-[1.25rem] border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-[0.925rem] font-bold text-gray-800">Daftar Mata Pelajaran</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead><tr><th class="<?= $thClass ?>">#</th><th class="<?= $thClass ?>">Nama Mapel</th><th class="<?= $thClass ?>">Kode</th><th class="<?= $thClass ?>">Guru</th></tr></thead>
            <tbody>
                <?php if ($subjects): ?>
                    <?php foreach ($subjects as $i => $s): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="<?= $tdClass ?> text-gray-400"><?= $i + 1 ?></td>
                        <td class="<?= $tdClass ?> font-semibold"><?= e($s['name']) ?></td>
                        <td class="<?= $tdClass ?>"><span class="bg-brand-50 text-brand-700 px-2 py-0.5 rounded-md text-xs font-bold"><?= e($s['code']) ?></span></td>
                        <td class="<?= $tdClass ?> text-gray-500"><?= e($s['teacher_name'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center py-12 text-gray-400 text-sm">Belum ada mata pelajaran.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once '../layout/footer.php'; ?>
