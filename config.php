<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'acahub_native');
define('DB_USER', 'root');
define('DB_PASS', '');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }
    return $pdo;
}

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_acahub'])) {
    $decoded = base64_decode($_COOKIE['remember_acahub']);
    $parts   = explode(':', $decoded);
    if (count($parts) === 2 && $parts[1] === 'secret') {
        $userId = (int)$parts[0];
        $pdo    = getDB();
        $stmt   = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user   = $stmt->fetch();
        if ($user) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_role'] = $user['role'];
        }
    }
}

require_once __DIR__ . '/helpers.php';
