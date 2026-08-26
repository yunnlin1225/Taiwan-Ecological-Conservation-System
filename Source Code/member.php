<?php
session_start();

$host = '127.0.0.1';
$dbname = 'school';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ 資料庫連線失敗: " . $e->getMessage());
}

$action = $_GET['action'] ?? '';
$msg = '';
$loggedIn = isset($_SESSION['user']);

if ($action === 'register' && $_POST) {
    try {
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (u_account, u_password, u_name, u_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['account'],
            $hashedPassword,
            $_POST['name'],
            $_POST['address']
        ]);
        $msg = "✅ 註冊成功！請登入。";
    } catch (PDOException $e) {
        $msg = "❌ 註冊失敗：" . $e->getMessage();
    }
}

if ($action === 'login' && $_POST) {
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE u_account = ?");
        $stmt->execute([$_POST['account']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($_POST['password'], $user['u_password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['u_no'] = $user['u_no']; // ➕ 提供給留言使用
            header("Location: multidata.php");     // ✅ 登入後導向 multidata.php
            exit;
        } else {
            $msg = "❌ 登入失敗，帳號或密碼錯誤。";
        }
    } catch (PDOException $e) {
        $msg = "❌ 登入失敗：" . $e->getMessage();
    }
}

if ($action === 'edit' && $_POST && $loggedIn) {
    try {
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET u_account = ?, u_password = ?, u_name = ?, u_address = ? WHERE u_no = ?");
        $stmt->execute([
            $_POST['account'],
            $hashedPassword,
            $_POST['name'],
            $_POST['address'],
            $_SESSION['user']['u_no']
        ]);

        // 更新 session 內容
        $_SESSION['user']['u_account'] = $_POST['account'];
        $_SESSION['user']['u_name'] = $_POST['name'];
        $_SESSION['user']['u_address'] = $_POST['address'];

        $msg = "✅ 修改成功";
    } catch (PDOException $e) {
        $msg = "❌ 修改失敗：" . $e->getMessage();
    }
}

if ($action === 'delete' && $loggedIn) {
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE u_no = ?");
        $stmt->execute([$_SESSION['user']['u_no']]);
        session_destroy();
        header("Location: index.html");
        exit;
    } catch (PDOException $e) {
        $msg = "❌ 刪除失敗：" . $e->getMessage();
    }
}

if ($action === 'logout') {
    session_destroy();
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">  
    <title>會員系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2 class="mb-4">會員系統</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if (!$loggedIn && ($action === 'register')): ?>
        <form method="post" action="?action=register">
            <h4>註冊</h4>
            <input name="account" class="form-control mb-2" placeholder="帳號" required>
            <div class="input-group mb-2">
                <input name="password" type="password" class="form-control password-field" placeholder="密碼" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)">👁️</button>
            </div>
            <input name="name" class="form-control mb-2" placeholder="姓名" required>
            <input name="address" class="form-control mb-2" placeholder="地址" required>
            <button class="btn btn-success">註冊</button>
            <a href="member.php" class="btn btn-secondary">返回登入</a>
        </form>

    <?php elseif (!$loggedIn && ($action === 'login' || $action === '')): ?>
        <form method="post" action="?action=login">
            <h4>登入</h4>
            <input name="account" class="form-control mb-2" placeholder="帳號" required>
            <div class="input-group mb-2">
                <input name="password" type="password" class="form-control password-field" placeholder="密碼" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)">👁️</button>
            </div>
            <button class="btn btn-primary">登入</button>
            <a href="?action=register" class="btn btn-link">註冊</a>
        </form>

    <?php elseif ($action === 'edit' && $loggedIn): ?>
        <form method="post" action="?action=edit">
            <h4>修改資料</h4>
            <input name="account" value="<?= htmlspecialchars($_SESSION['user']['u_account']) ?>" class="form-control mb-2" required>
            <div class="input-group mb-2">
                <input name="password" type="password" class="form-control password-field" placeholder="新密碼" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)">👁️</button>
            </div>
            <input name="name" value="<?= htmlspecialchars($_SESSION['user']['u_name']) ?>" class="form-control mb-2" required>
            <input name="address" value="<?= htmlspecialchars($_SESSION['user']['u_address']) ?>" class="form-control mb-2" required>
            <button class="btn btn-warning">修改</button>
            <a href="member.php" class="btn btn-secondary">取消</a>
        </form>

    <?php else: ?>
        <h4>歡迎，<?= htmlspecialchars($_SESSION['user']['u_name']) ?></h4>
        <p>帳號：<?= htmlspecialchars($_SESSION['user']['u_account']) ?></p>
        <p>地址：<?= htmlspecialchars($_SESSION['user']['u_address']) ?></p>
        <a href="?action=edit" class="btn btn-warning">修改資料</a>
        <a href="?action=delete" class="btn btn-danger" onclick="return confirm('確定要刪除帳號？')">刪除帳號</a>
        <a href="?action=logout" class="btn btn-secondary">登出</a>
    <?php endif; ?>

    <script>
    function togglePassword(button) {
        const input = button.previousElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '🙈';
        } else {
            input.type = 'password';
            button.textContent = '👁️';
        }
    }
    </script>
</body>
</html>
