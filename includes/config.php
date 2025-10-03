<?php
// CEK SUDAH DI-DEFINE BELUM - ANTI DOUBLE
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}

if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}

if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'warung_mama_eryan');
}

// CEK SUDAH DI-LOAD BELUM - ANTI DOUBLE
if (!defined('CONFIG_LOADED')) {
    define('CONFIG_LOADED', true);
    
    // Koneksi ke Database
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Koneksi database gagal: " . $e->getMessage());
    }

    // Start session - CEK SUDAH ACTIVE BELUM
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Set timezone
    date_default_timezone_set('Asia/Jakarta');
}
?>