<?php
// utils/fix_hash.php — helper untuk reset password development
// Gunakan cost 4 — jauh lebih cepat untuk development
$pdo = new PDO("mysql:host=localhost;dbname=acahub_native;charset=utf8mb4","root","");
$hash = password_hash('password', PASSWORD_BCRYPT, ['cost' => 4]);
$pdo->prepare("UPDATE users SET password = ? WHERE id IN (1,2,3)")->execute([$hash]);
$rows = $pdo->query("SELECT id, name, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo "OK: {$r['name']} ({$r['role']})\n";
}
echo "Selesai! Semua password = 'password'\n";
