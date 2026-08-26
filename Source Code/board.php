<?php
date_default_timezone_set('Asia/Taipei');

$filename = 'messages.json';

// 儲存留言
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $message = $_POST['message'] ?? '';

    $messages = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];

    $messages[] = [
        'name' => $name,
        'message' => $message,
        'time' => date("h:i:s a Y-m-d")
    ];

    file_put_contents($filename, json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    header("Location: board.php");
    exit();
}

// 讀取留言
$messages = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];

// 排序處理
$sort = $_GET['sort'] ?? 'new';
$displayMessages = ($sort === 'old') ? $messages : array_reverse($messages);
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>留言板</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        body {
            background-color: #e4fbe1;
            font-family: sans-serif;
            padding: 20px;
        }
        h2 {
            color: #1f703f;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #92c29c;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        button {
            background-color: #a5e1aa;
            border: 1px solid #6fb06f;
            padding: 12px 30px;
            font-size: 16px;
            color: #075d07;
            cursor: pointer;
            border-radius: 10px;
        }
        button:hover {
            background-color: #91d09a;
        }
        .message {
            background-color: #d0eec9;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .message strong {
            font-size: 18px;
        }
        .time {
            float: right;
            background-color: #505b66;
            color: white;
            border-radius: 15px;
            padding: 3px 10px;
            font-size: 14px;
        }
        .controls {
            margin-top: 15px;
        }
        .controls form {
            display: inline-block;
        }
        .controls button {
            margin-right: 10px;
        }
        .delete-btn {
            margin-top: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<?php include('navbar.html'); ?>

<div class="container mt-4">
    <h2>寫些對動物生態的想法...</h2>

    <form method="post" action="board.php">
        <input type="text" name="name" placeholder="姓名" required>
        <textarea name="message" placeholder="留言內容..." required></textarea>
        <button type="submit">送出留言</button>
    </form>

    <!-- 排序控制按鈕 -->
    <div class="controls">
        <form method="get">
            <input type="hidden" name="sort" value="new">
            <button type="submit" class="btn btn-primary">由新至舊</button>
        </form>
        <form method="get">
            <input type="hidden" name="sort" value="old">
            <button type="submit" class="btn btn-secondary">由舊至新</button>
        </form>
    </div>

    <!-- 顯示留言區塊 -->
    <?php
    $total = count($messages);
    foreach ($displayMessages as $i => $msg) {
        $realIndex = ($sort === 'old') ? $i : ($total - 1 - $i);
        echo '<div class="message">';
        echo "<strong>" . htmlspecialchars($msg['name']) . "</strong><br>";
        echo "<div>" . nl2br(htmlspecialchars($msg['message'])) . "</div>";
        echo '<div class="time">' . $msg['time'] . '</div>';

        // 刪除按鈕表單
        echo "<form method='get' action='boardAdd.php' onsubmit='return confirm(\"確定要刪除這則留言嗎？\");'>";
        echo "<input type='hidden' name='delete' value='$realIndex'>";
        echo "<button type='submit' class='delete-btn'>刪除留言</button>";
        echo "</form>";

        echo '</div>';
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>
