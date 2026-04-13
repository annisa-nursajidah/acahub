<?php
// ── 1. SETUP ──────────────────────────────────────
$pageTitle  = 'Kelola Users';
$activePage = 'users';

// Load config dulu (tanpa output) untuk handle POST sebelum header
require_once __DIR__ . '/../config.php';
requireLogin();
$user = currentUser();
$pdo  = getDB();

$isAdmin   = ($user['role'] === 'admin');
$isTeacher = ($user['role'] === 'teacher');

if (!$isAdmin && !$isTeacher) {
    redirect('/native/pages/dashboard.php');
}

// ── 2. HANDLE FORM (POST) — sebelum output HTML ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    verifyCsrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $uid = (int)$_POST['uid'];
        if ($uid !== $user['id']) {
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$uid]);
            setFlash('success', 'User berhasil dihapus.');
        } else {
            setFlash('error', 'Tidak bisa hapus akun sendiri.');
        }
        redirect('/native/pages/users.php');
    }

    if ($action === 'create') {
        $name  = trim($_POST['name']     ?? '');
        $email = trim($_POST['email']    ?? '');
        $role  = $_POST['role']          ?? 'student';
        $pass  = $_POST['password']      ?? 'password123';

        $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)")
            ->execute([$name, $email, password_hash($pass, PASSWORD_BCRYPT), $role]);
        setFlash('success', 'User berhasil dibuat.');
        redirect('/native/pages/users.php');
    }
}

// Baru include header (output HTML)
require_once '../layout/header.php';

// ── 3. AMBIL DATA ─────────────────────────────────
$roleFilter = $_GET['role'] ?? '';
$search     = $_GET['q']    ?? '';

$sql    = "SELECT * FROM users WHERE 1=1";
$params = [];

if ($roleFilter && in_array($roleFilter, ['admin', 'teacher', 'student', 'parent'])) {
    $sql    .= " AND role = ?";
    $params[] = $roleFilter;
}
if ($search) {
    $sql    .= " AND (name LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$sql .= " ORDER BY created_at DESC";

$stmt     = $pdo->prepare($sql);
$stmt->execute($params);
$userList = $stmt->fetchAll();

$avatarColors = ['admin' => 'bg-accent-100 text-accent-600', 'teacher' => 'bg-brand-100 text-brand-700', 'student' => 'bg-green-50 text-green-600', 'parent' => 'bg-purple-50 text-purple-600'];
$badgeColors  = ['admin' => 'bg-yellow-100 text-yellow-800', 'teacher' => 'bg-brand-50 text-brand-700',  'student' => 'bg-green-50 text-green-700',  'parent' => 'bg-purple-50 text-purple-700'];
?>

<!-- ── 4. HTML OUTPUT ──────────────────────────────── -->
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <h1 class="text-xl font-extrabold">👤 Kelola Users</h1>
    <div class="flex gap-3 items-center flex-wrap">
        <form method="GET" action="users.php" class="flex gap-2 flex-wrap">
            <div class="flex items-center gap-2 bg-white border-[1.5px] border-gray-200 rounded-[0.875rem] px-4 py-2.5">
                <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari nama / email..." class="border-none outline-none font-inter text-sm bg-transparent text-gray-900 w-[200px]">
            </div>
            <select name="role" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-[0.875rem] border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-700 outline-none cursor-pointer">
                <option value="">Semua Peran</option>
                <option value="admin"   <?= $roleFilter === 'admin'   ? 'selected' : '' ?>>Admin</option>
                <option value="teacher" <?= $roleFilter === 'teacher' ? 'selected' : '' ?>>Guru</option>
                <option value="student" <?= $roleFilter === 'student' ? 'selected' : '' ?>>Siswa</option>
                <option value="parent"  <?= $roleFilter === 'parent'  ? 'selected' : '' ?>>Orang Tua</option>
            </select>
        </form>
        <?php if ($isAdmin): ?>
        <button onclick="document.getElementById('form-user').style.display = document.getElementById('form-user').style.display === 'none' ? 'block' : 'none'"
                class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-[0.875rem] text-[0.85rem] font-semibold bg-accent-500 text-white border-none cursor-pointer font-inter hover:bg-accent-600 transition-all">
            + Tambah User
        </button>
        <?php endif; ?>
    </div>
</div>

<?php if ($isAdmin): ?>
<div id="form-user" style="display:none; margin-bottom:1.5rem;">
    <div class="bg-white rounded-[1.25rem] border border-gray-100 p-6">
        <h3 class="text-[0.925rem] font-bold text-gray-700 mb-4">+ Tambah Pengguna Baru</h3>
        <form method="POST" action="users.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action" value="create">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5"><label class="text-[0.8rem] font-medium text-gray-600">Nama Lengkap</label><input type="text" name="name" placeholder="Nama lengkap" required class="px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500"></div>
                <div class="flex flex-col gap-1.5"><label class="text-[0.8rem] font-medium text-gray-600">Email</label><input type="email" name="email" placeholder="email@sekolah.id" required class="px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500"></div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[0.8rem] font-medium text-gray-600">Peran</label>
                    <select name="role" class="px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500">
                        <option value="student">Siswa</option>
                        <option value="teacher">Guru</option>
                        <option value="parent">Orang Tua</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1.5"><label class="text-[0.8rem] font-medium text-gray-600">Password Awal</label><input type="password" name="password" value="password123" class="px-3.5 py-3 rounded-xl border-[1.5px] border-gray-200 font-inter text-sm bg-white text-gray-900 outline-none focus:border-brand-500"></div>
            </div>
            <button type="submit" class="mt-4 px-7 py-3 rounded-xl text-sm font-bold bg-accent-500 text-white border-none cursor-pointer font-inter hover:bg-accent-600 transition-all">Simpan</button>
        </form>
    </div>
</div>
<?php endif; ?>

<p class="text-[0.825rem] text-gray-400 mb-4"><?= count($userList) ?> user ditemukan</p>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php if ($userList): ?>
        <?php foreach ($userList as $u):
            $ac = $avatarColors[$u['role']] ?? 'bg-gray-100 text-gray-600';
            $bc = $badgeColors[$u['role']]  ?? 'bg-gray-100 text-gray-600';
        ?>
        <div class="bg-white rounded-[1.25rem] border border-gray-100 p-5 flex items-center gap-4 hover:shadow-[0_6px_24px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all">
            <div class="w-12 h-12 rounded-full flex-shrink-0 flex items-center justify-center text-lg font-extrabold uppercase <?= $ac ?>">
                <?= strtoupper(substr($u['name'], 0, 1)) ?>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-[0.925rem] font-bold text-gray-900 truncate"><?= e($u['name']) ?></div>
                <div class="text-[0.775rem] text-gray-400 mt-0.5 truncate"><?= e($u['email']) ?></div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[0.65rem] font-bold capitalize <?= $bc ?>"><?= e($u['role']) ?></span>
                    <span class="text-[0.7rem] text-gray-400"><?= date('d M Y', strtotime($u['created_at'])) ?></span>
                </div>
            </div>
            <?php if ($isAdmin && $u['id'] !== $user['id']): ?>
            <form method="POST" action="users.php" onsubmit="return confirm('Hapus user ini?')">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="uid" value="<?= $u['id'] ?>">
                <button type="submit" class="bg-transparent border-none cursor-pointer text-gray-300 hover:text-red-500 p-1 rounded-md transition-colors" title="Hapus">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-full text-center py-16 text-gray-400">Tidak ada user ditemukan.</div>
    <?php endif; ?>
</div>

<?php require_once '../layout/footer.php'; ?>
