<?php
$host = 'kodama.proxy.rlwy.net';
$port = '39062';
$db   = 'railway';
$user = 'postgres';
$pass = 'YYpCBtPAJowOhSzPOmBznynFEXKxSwcn';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=prefer";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    echo "--- KONEKSI KE RAILWAY BERHASIL! ---\n";
    
} catch (PDOException $e) {
    echo "--- KONEKSI GAGAL! ---\n";
    echo "Pesan Error: " . $e->getMessage() . "\n";
}