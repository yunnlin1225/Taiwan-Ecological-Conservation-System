<?php

// ==========================================
// MySQL 資料庫連線設定
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

    // 發生錯誤時拋出例外
    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}

?>