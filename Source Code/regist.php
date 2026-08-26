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
//開發網頁的注意點是: 永遠不要相信使用者輸入了甚麼東西，請記住，使用者輸入完後要再驗證一次，以防止資料庫注入。
function test_input($data)
{
    $data = trim($data); //消除兩側空白字符
    $data = stripslashes($data); //消除反斜杠
    $data = htmlspecialchars($data); //轉換 HTML 特殊符號為僅能顯示用的編碼
    return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["regist"])) {
    include "connect.php";
    //查詢資料庫內是否存在相同的帳號
    $sql = "SELECT U_ACCOUNT FROM USERS WHERE U_ACCOUNT = :u_account";
    $stmt = $conn->prepare($sql);
    $test = test_input($_POST["u_account"]);
    //將$_POST['u_account']代入至:u_account ; $_POST['u_account']是接收上面name=u_account的表單內資料
    $stmt->bindParam(":u_account", $test);
    $stmt->execute(); //prepare、參數綁定完後即可執行select。
    $colcount = $stmt->rowCount(); //查看回傳行數，如果傳回0就代表沒有找到東西。
    //如果返回的行數為0，代表沒有找到該帳號，即可註冊。
    if ($colcount == 0) {
        //將$_POST['u_account']預處理後，宣告為$u_acoount ; $_POST['u_account']是接收上面name=u_account的表單內資料
        $u_account = test_input($_POST["u_account"]);
        //將$_POST['u_password']預處理、密碼加密後(不會以明文顯示)，宣告為$u_password ; $_POST['u_account']是接收上面name=u_password的表單內資料
        $u_password = password_hash(
            test_input($_POST["u_password"]),
            PASSWORD_DEFAULT
        );
        //將$_POST['u_name']預處理後，宣告為$u_name ; $_POST['u_name']是接收上面name=u_name的表單內資料
        $u_name = test_input($_POST["u_name"]);
        //將$_POST['u_address']預處理後，宣告為$u_address ; $_POST['u_address']是接收上面name=u_address的表單內資料
        $u_address = test_input($_POST["u_address"]);
        //先將SQL Statement 先轉化為樣板，之後再對其賦值。
        $sql =
            "INSERT INTO USERS(U_ACCOUNT, U_PASSWORD, U_NAME, U_ADDRESS) VALUES(:u_account, :u_password, :u_name, :u_address)";
        //先傳給sql引擎說: 我等等要執行喔，你先準備一下。
        $stmt = $conn->prepare($sql);
        //將帳號變數$u_account賦值於sql樣板的:u_account。
        $stmt->bindParam(":u_account", $u_account);
        //將密碼變數$u_password賦值於sql樣板的:u_password。
        $stmt->bindParam(":u_password", $u_password);
        //將姓名變數$u_name賦值於sql樣板的:u_name。
        $stmt->bindParam(":u_name", $u_name);
        //將地址變數$u_address賦值於sql樣板的:u_address。
        $stmt->bindParam(":u_address", $u_address);
        //賦值完後即可執行。
        $result = $stmt->execute();
        if ($result) {
            echo "<script>alert('註冊成功!');location.href='index.html';</script>";
        } else {
            $msg =
                '<div class="alert alert-danger mt-2" role="alert">因不明原因，註冊失敗</div>';
        }
        //與資料庫連接完後取消連接。
        $conn = null;
    } else {
        $msg =
            '<div class="alert alert-danger mt-2" role="alert">註冊失敗，帳號已經存在!</div>';
    }
}
?>

        <div class="container">
            <div class="row w-100 vh-100 justify-content-center align-items-center">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold fs-3">註冊</h5>
                        <form class="row g-3" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="col-md-6">
                                <label for="inputAcc" class="form-label">帳號</label>
                                <input type="text" class="form-control" id="inputAcc" name="u_account" required>
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">密碼</label>
                                <input type="password" class="form-control" id="inputPassword4" name="u_password" required>
                            </div>
                            <div class="col-md-12">
                                <label for="inputName" class="form-label">姓名</label>
                                <input type="text" class="form-control" id="inputName" name="u_name" required>
                            </div>
                            <div class="col-md-12">
                                <label for="inputState" class="form-label">地址</label>
                                <input type="text" class="form-control" id="inputState" name="u_address" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" name="regist">註冊</button>
                                <a href="login.php" class="btn btn-secondary">返回登入</a>
                            </div>
                        </form>
                        <?php
                        //顯示錯誤資訊
                        $msg = $msg ?? '';
                        echo $msg;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
</html>