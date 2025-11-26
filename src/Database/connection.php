<?php
namespace Database;
function getPDO(){
    $cfg = parse_ini_file(__DIR__ . '/../../config/app.ini');
    $host = $cfg['DB_HOST'] ?? '127.0.0.1';
    $db   = $cfg['DB_NAME'] ?? 'iconic_uploads';
    $user = $cfg['DB_USER'] ?? 'iconic';
    $pass = $cfg['DB_PASS'] ?? 'Icon1cPass!';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION]);
    return $pdo;
}
