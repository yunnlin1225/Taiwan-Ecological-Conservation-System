<?php

// ==========================================
// MySQL Database Connection
// ==========================================

$host = "localhost";
$dbname = "school";
$username = "root";
$password = "";

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}

?>