<?php
date_default_timezone_set('Asia/Taipei');

$filename = 'messages.json';

// 處理刪除留言
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];

    if (file_exists($filename)) {
        $messages = json_decode(file_get_contents($filename), true);

        // 檢查索引是否合法
        if ($index >= 0 && $index < count($messages)) {
            array_splice($messages, $index, 1); // 移除指定索引的留言
            file_put_contents($filename, json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    // 刪除後導回留言板頁面
    header("Location: board.php");
    exit();
}
?>
