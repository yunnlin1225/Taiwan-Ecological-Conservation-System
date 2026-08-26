<?php
//開啟session，用於存放資訊
session_start();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>會員系統</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
        <?php
include "connect.php";
//將$_SESSION['u_account']提出存放於$u_account
$u_account = $_SESSION["u_account"];
$sql = "SELECT * FROM users WHERE u_account = :u_account ";
$exec = $conn->prepare($sql);
//將u_account代入至:u_account
$exec->bindParam(":u_account", $u_account);
$exec->execute(); //執行select語法
$row = $exec->fetch(); //抓值存放$row陣列中

//開發網頁的注意點是: 永遠不要相信使用者輸入了甚麼東西，請記住，使用者輸入完後要再驗證一次，以防止資料庫注入。
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
//按下update按鈕執行以下程式
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $sql =
        "UPDATE users SET u_name = :u_name ,u_address = :u_address WHERE u_account= :u_account ";
    $stmt = $conn->prepare($sql);
    $u_name = test_input($_POST["u_name"]);
    $u_address = test_input($_POST["u_address"]);
    //將u_account代入至:u_account
    $stmt->bindParam(":u_account", $u_account);
    $stmt->bindParam(":u_name", $u_name);
    $stmt->bindParam(":u_address", $u_address);
    $result = $stmt->execute(); //執行UPDATE語法
    if ($result) {
        header("Location: member.php"); //跳轉回member.php
    } else {
        echo "<script>showAlert(); </script>";
    }
    $conn = null;
    $stmt = null;
}

//按下delete按鈕執行以下程式
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $sql = "DELETE FROM users WHERE u_account = :u_account ";
    $stmt = $conn->prepare($sql);
    //將u_account代入至:u_account
    $stmt->bindParam(":u_account", $u_account);
    $result = $stmt->execute(); //執行DELETE語法
    if ($result) {
        echo "<script>alert('刪除完成');location.href='login.php';</script>";
    } else {
        echo "<script>showAlert(); </script>";
    }
    $conn = null;
    $stmt = null;
}
?>
        <div class="container">
            <div class="row  vh-100 w-100 justify-content-center align-items-center">
                <div class="col-12">
                    <div class="card mx-auto border border-4 border-secondary" style="width: 22rem;">
                        <img src="img/peko_christ.png" class="card-img-top border border-5" alt="...">
                        <div class="card-body">
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                <div class="row mb-3">
                                    <label for="inputacc" class="col-sm-2 col-form-label">帳號</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputacc" value="<?php echo $row['u_account'] ?? '尚未登入'; ?>" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputname" class="col-sm-2 col-form-label">姓名</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputname" name="u_name" value="<?php echo $row['u_name'] ?? '尚未登入' ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputAdd" class="col-sm-2 col-form-label">地址</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputAdd" name="u_address" value="<?php echo $row['u_address'] ?? '尚未登入'; ?>" required>
                                    </div>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><input type="submit" class="btn btn-primary w-100" name="update" value="修改"></li>
                                    <li class="list-group-item"><input type="submit" class="btn btn-danger w-100" name="delete" value="刪除"></li>
                                    <li class="list-group-item"><a href="member.php" class="btn btn-secondary w-100" name="back">返回</a></li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">會員系統</strong>
                    <small>剛剛</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    資料庫修改錯誤!
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <script>
        function showAlert(){
                const toastLiveExample = document.getElementById('liveToast')
                const toast = new bootstrap.Toast(toastLiveExample)
                toast.show()
        }
        </script>
    </body>
    
</html>