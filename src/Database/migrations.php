<?php
// Run: php src/Database/migrations.php
require_once __DIR__ . '/connection.php';
$pdo = \Database\getPDO();
$pdo->exec("CREATE TABLE IF NOT EXISTS uploads (
    id VARCHAR(255) PRIMARY KEY,
    filename VARCHAR(255),
    original_name VARCHAR(255),
    size BIGINT,
    sha256 VARCHAR(255),
    status VARCHAR(50),
    risk_level VARCHAR(50),
    message TEXT,
    created_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
echo "Migration complete\n";
