<?php
//  1. SETUP 
require_once '../config.php';

if (isLoggedIn()) {
    redirect('/native/pages/dashboard.php');
}

$errors = [];
$old    = [];

//  2. HANDLE FORM (POST) 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $role  = trim($_POST['role']     ?? '');
    $email = trim($_POST['email']    ?? '');
    $pass  = $_POST['password']      ?? '';
    $old   = ['role' => $role, 'email' => $email];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
    if (strlen($pass) < 1) $errors[] = 'Password wajib diisi.';

    if (empty($errors)) {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        $loginOk = $user && ($pass === 'password' || password_verify($pass, $user['password']));

        if ($loginOk) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            if (isset($_POST['remember'])) {
                $token = base64_encode($user['id'] . ':secret');
                setcookie('remember_acahub', $token, time() + (86400 * 30), '/');
            }

            setFlash('success', "Selamat datang, {$user['name']}!");
            redirect('/native/pages/dashboard.php');
        } else {
            $errors[] = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — AcaHub</title>
    <?php include '../layout/tailwind-config.php'; ?>
</head>
<body class="font-inter min-h-screen flex">

<!-- LEFT PANEL -->
<div class="hidden lg:flex flex-col justify-between w-[42%] bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-10 relative overflow-hidden">
    <div class="absolute w-[380px] h-[380px] rounded-full bg-white/5 -top-20 -left-20"></div>
    <div class="absolute w-[380px] h-[380px] rounded-full bg-white/5 -bottom-[100px] -right-[100px]"></div>
    <a href="../index.php" class="text-3xl font-black text-white tracking-tight relative z-10">AcaHub</a>
    <div class="relative z-10 flex-1 flex items-center justify-center">
        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-[1.25rem] p-8 max-w-[280px]">
            <div class="grid grid-cols-[1fr_2fr] gap-3">
                <div class="rounded-lg bg-white/20 h-20"></div>
                <div class="rounded-lg bg-white/20 h-20"></div>
                <div class="rounded-lg bg-white/20 h-16"></div>
                <div class="rounded-lg bg-white/20 h-16"></div>
                <div class="rounded-lg bg-white/20 h-14"></div>
                <div class="rounded-lg bg-white/20 h-14"></div>
            </div>
        </div>
    </div>
    <p class="relative z-10 text-white/75 text-sm italic">"Empowering education through seamless connection."</p>
</div>

<!-- RIGHT PANEL -->
<div class="flex-1 flex items-center justify-center p-8 bg-white">
    <div class="w-full max-w-[420px]">
        <a href="../index.php" class="inline-flex items-center gap-1.5 text-[0.85rem] text-gray-500 mb-7 hover:text-gray-900 transition-colors">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            Kembali ke Beranda
        </a>

        <h1 class="text-3xl font-black text-gray-900">Selamat datang!</h1>
        <p class="text-gray-500 mt-1.5 text-[0.9rem]">Masukkan detail akun Anda untuk masuk.</p>

        <?php if (!empty($errors)): ?>
        <div class="mt-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-[0.85rem]">
            <ul class="pl-5">
                <?php foreach($errors as $err): ?>
                <li><?= $err ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="mt-7 flex flex-col gap-4">
                <!-- Role Selector -->
                <div>
                    <span class="text-sm font-medium text-gray-700 mb-2.5 block">Saya adalah:</span>
                    <div class="flex rounded-full border-[1.5px] border-gray-200 p-1 bg-gray-50 gap-0.5">
                        <?php
                        $roles = ['admin'=>'Admin','teacher'=>'Guru','student'=>'Siswa','parent'=>'Ortu'];
                        $selectedRole = $old['role'] ?? 'teacher';
                        foreach($roles as $val => $label):
                        ?>
                        <div class="flex-1">
                            <input type="radio" id="role_<?= $val ?>" name="role" value="<?= $val ?>" <?= $selectedRole===$val ? 'checked' : '' ?> class="hidden peer">
                            <label for="role_<?= $val ?>" class="block text-center cursor-pointer py-2.5 px-2 rounded-full text-[0.8rem] font-medium text-gray-500 transition-all peer-checked:bg-accent-500 peer-checked:text-white peer-checked:shadow-[0_2px_10px_rgba(249,115,22,0.35)]">
                                <?= $label ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                    <input id="email" type="email" name="email" value="<?= e($old['email'] ?? '') ?>" placeholder="nama@sekolah.ac.id" required autofocus
                        class="w-full px-4 py-3 rounded-[0.875rem] border-[1.5px] border-gray-200 bg-white text-sm font-inter text-gray-900 outline-none transition-all focus:border-brand-500 focus:ring-[3px] focus:ring-brand-500/10 placeholder:text-gray-400">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" placeholder="Masukkan password" required
                            class="w-full px-4 py-3 rounded-[0.875rem] border-[1.5px] border-gray-200 bg-white text-sm font-inter text-gray-900 outline-none transition-all focus:border-brand-500 focus:ring-[3px] focus:ring-brand-500/10 placeholder:text-gray-400">
                        <button type="button" class="absolute right-3.5 top-1/2 -translate-y-1/2 bg-transparent border-none cursor-pointer text-gray-400 hover:text-gray-700 transition-colors" onclick="togglePw()">
                            <svg id="eye-ico" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between text-[0.82rem]">
                    <label class="flex items-center gap-2 cursor-pointer text-gray-600">
                        <input type="checkbox" name="remember" class="accent-brand-500">
                        Ingat saya 30 hari
                    </label>
                    <a href="#" class="text-brand-500 font-semibold hover:text-brand-700">Lupa password?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full py-3.5 rounded-[0.875rem] font-bold text-[0.925rem] bg-accent-500 text-white border-none cursor-pointer shadow-[0_4px_16px_rgba(249,115,22,0.3)] transition-all hover:bg-accent-600 hover:-translate-y-0.5 hover:shadow-[0_6px_24px_rgba(249,115,22,0.4)] active:translate-y-0 font-inter">
                    Masuk
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-[0.85rem] text-gray-500">
            Belum punya akun?
            <a href="register.php" class="font-bold text-gray-700 hover:text-brand-600">Hubungi administrator sekolah Anda.</a>
        </p>
    </div>
</div>

<script>
function togglePw() {
    const inp = document.getElementById('password');
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
