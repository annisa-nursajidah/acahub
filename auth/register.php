<?php
//  1. SETUP 
require_once '../config.php';

if (isLoggedIn()) redirect('/native/pages/dashboard.php');

$errors = [];
$old    = [];

//  2. HANDLE FORM (POST) 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name    = trim($_POST['name']             ?? '');
    $email   = trim($_POST['email']            ?? '');
    $pass    = $_POST['password']              ?? '';
    $confirm = $_POST['password_confirmation'] ?? '';
    $role    = $_POST['role']                  ?? 'student';
    $old     = compact('name', 'email', 'role');

    if (strlen($name) < 3)  $errors[] = 'Nama minimal 3 karakter.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
    if (strlen($pass) < 8)  $errors[] = 'Password minimal 8 karakter.';
    if ($pass !== $confirm)  $errors[] = 'Konfirmasi password tidak cocok.';
    if (!in_array($role, ['admin', 'teacher', 'student', 'parent'])) $errors[] = 'Pilih peran yang valid.';

    if (empty($errors)) {
        $pdo = getDB();
        $chk = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $chk->execute([$email]);

        if ($chk->fetch()) {
            $errors[] = 'Email sudah terdaftar. Silakan <a href="login.php">masuk</a>.';
        } else {
            $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)") 
                ->execute([$name, $email, password_hash($pass, PASSWORD_BCRYPT), $role]);
            setFlash('success', 'Pendaftaran berhasil! Silakan masuk.');
            redirect('/native/auth/login.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — AcaHub</title>
    <?php include '../layout/tailwind-config.php'; ?>
</head>
<body class="font-inter min-h-screen flex">

<!-- LEFT PANEL -->
<div class="hidden lg:flex flex-col justify-between w-[42%] bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900 p-10 relative overflow-hidden">
    <div class="absolute w-[380px] h-[380px] rounded-full bg-white/5 -top-20 -left-20"></div>
    <div class="absolute w-[380px] h-[380px] rounded-full bg-white/5 -bottom-[100px] -right-[100px]"></div>
    <a href="../index.php" class="text-3xl font-black text-white tracking-tight relative z-10">AcaHub</a>
    <div class="relative z-10 flex-1 flex items-center justify-center">
        <div class="text-center text-white/90">
            <div class="w-24 h-24 rounded-[1.25rem] mx-auto mb-5 bg-white/10 backdrop-blur-lg border border-white/20 flex items-center justify-center">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="text-white/80"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
            </div>
            <h2 class="text-xl font-extrabold">Bergabung dengan AcaHub</h2>
            <p class="text-white/60 text-[0.85rem] mt-2 max-w-[240px] mx-auto leading-relaxed">Buat akun Anda dan mulai terhubung dengan ekosistem sekolah hari ini.</p>
        </div>
    </div>
    <p class="relative z-10 text-white/70 text-sm italic">"Kualitas pendidikan adalah fondasi masa depan yang lebih baik."</p>
</div>

<!-- RIGHT PANEL -->
<div class="flex-1 flex items-center justify-center p-8 bg-white overflow-y-auto">
    <div class="w-full max-w-[420px] py-4">
        <a href="../index.php" class="inline-flex items-center gap-1.5 text-[0.85rem] text-gray-500 mb-7 hover:text-gray-900 transition-colors">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            Kembali ke Beranda
        </a>

        <h1 class="text-3xl font-black text-gray-900">Buat Akun Baru</h1>
        <p class="text-gray-500 mt-1.5 text-[0.9rem]">Mulai gunakan AcaHub gratis hari ini.</p>

        <?php if (!empty($errors)): ?>
        <div class="mt-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-[0.85rem]">
            <ul class="pl-5">
                <?php foreach($errors as $err): ?>
                <li><?= $err ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="mt-7 flex flex-col gap-4">
                <!-- Role -->
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2.5 block">Saya adalah:</span>
                    <div class="flex rounded-full border-[1.5px] border-gray-200 p-1 bg-gray-50 gap-0.5">
                        <?php
                        $roles = ['admin'=>'Admin','teacher'=>'Guru','student'=>'Siswa','parent'=>'Ortu'];
                        $sel   = $old['role'] ?? 'student';
                        foreach($roles as $val => $label):
                        ?>
                        <div class="flex-1">
                            <input type="radio" id="role_<?= $val ?>" name="role" value="<?= $val ?>" <?= $sel===$val?'checked':'' ?> class="hidden peer">
                            <label for="role_<?= $val ?>" class="block text-center cursor-pointer py-2.5 px-1 rounded-full text-[0.78rem] font-medium text-gray-500 whitespace-nowrap transition-all peer-checked:bg-accent-500 peer-checked:text-white peer-checked:shadow-[0_2px_10px_rgba(249,115,22,0.35)]">
                                <?= $label ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="<?= e($old['name'] ?? '') ?>" placeholder="Nama lengkap Anda" required autofocus
                        class="w-full px-4 py-3 rounded-[0.875rem] border-[1.5px] border-gray-200 bg-white text-sm font-inter text-gray-900 outline-none transition-all focus:border-brand-500 focus:ring-[3px] focus:ring-brand-500/10 placeholder:text-gray-400">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                    <input id="email" type="email" name="email" value="<?= e($old['email'] ?? '') ?>" placeholder="nama@sekolah.ac.id" required
                        class="w-full px-4 py-3 rounded-[0.875rem] border-[1.5px] border-gray-200 bg-white text-sm font-inter text-gray-900 outline-none transition-all focus:border-brand-500 focus:ring-[3px] focus:ring-brand-500/10 placeholder:text-gray-400">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" placeholder="Buat password (min 8 karakter)" required oninput="checkStrength(this.value)"
                        class="w-full px-4 py-3 rounded-[0.875rem] border-[1.5px] border-gray-200 bg-white text-sm font-inter text-gray-900 outline-none transition-all focus:border-brand-500 focus:ring-[3px] focus:ring-brand-500/10 placeholder:text-gray-400">
                    <div class="flex gap-1 mt-2">
                        <span id="s1" class="flex-1 h-1 rounded-sm bg-gray-200 transition-colors"></span>
                        <span id="s2" class="flex-1 h-1 rounded-sm bg-gray-200 transition-colors"></span>
                        <span id="s3" class="flex-1 h-1 rounded-sm bg-gray-200 transition-colors"></span>
                        <span id="s4" class="flex-1 h-1 rounded-sm bg-gray-200 transition-colors"></span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5" id="strength-text">Masukkan password</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password Anda" required
                        class="w-full px-4 py-3 rounded-[0.875rem] border-[1.5px] border-gray-200 bg-white text-sm font-inter text-gray-900 outline-none transition-all focus:border-brand-500 focus:ring-[3px] focus:ring-brand-500/10 placeholder:text-gray-400">
                </div>

                <!-- Terms -->
                <label class="flex items-start gap-2 text-[0.82rem] text-gray-600 cursor-pointer">
                    <input type="checkbox" required class="mt-0.5 accent-brand-500">
                    Saya menyetujui <a href="#" class="text-brand-600 font-semibold">Syarat &amp; Ketentuan</a> dan <a href="#" class="text-brand-600 font-semibold">Kebijakan Privasi</a> AcaHub.
                </label>

                <!-- Submit -->
                <button type="submit" class="w-full py-3.5 rounded-[0.875rem] font-bold text-[0.925rem] bg-accent-500 text-white border-none cursor-pointer shadow-[0_4px_16px_rgba(249,115,22,0.3)] transition-all hover:bg-accent-600 hover:-translate-y-0.5 hover:shadow-[0_6px_24px_rgba(249,115,22,0.4)] font-inter">
                    Buat Akun
                </button>
            </div>
        </form>

        <div class="flex items-center gap-4 my-4">
            <hr class="flex-1 border-t border-gray-200">
            <span class="text-xs text-gray-400">atau</span>
            <hr class="flex-1 border-t border-gray-200">
        </div>

        <p class="text-center text-[0.85rem] text-gray-500">
            Sudah punya akun?
            <a href="login.php" class="font-bold text-brand-600 hover:text-brand-700">Masuk di sini</a>
        </p>
    </div>
</div>

<script>
function checkStrength(val) {
    const bars  = [document.getElementById('s1'),document.getElementById('s2'),document.getElementById('s3'),document.getElementById('s4')];
    const txt   = document.getElementById('strength-text');
    let score   = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
    const labels = ['Sangat lemah','Lemah','Cukup','Kuat'];
    bars.forEach((b,i) => { b.style.background = i < score ? colors[score-1] : '#e5e7eb'; });
    txt.textContent = val.length ? labels[score-1] : 'Masukkan password';
    txt.style.color = val.length ? colors[score-1] : '#9ca3af';
}
</script>
</body>
</html>
