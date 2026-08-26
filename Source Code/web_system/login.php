<?php
//有用到session一定要在網頁一開始的地方就放。
session_start();
//每次近來這個頁面，session裡存的u_account變數都會被銷毀。
$_SESSION['u_account'] = '';
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
//開發網頁的注意點是: 永遠不要相信使用者輸入了甚麼東西，請記住，使用者輸入完後要再驗證一次，以防止資料庫注入。
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
//第一步驗證網頁發出來的訊號是否為post及使用者是否按了登入
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    //雖然用html require過，但還是用php再驗證一次有沒有輸入帳號及密碼
    $fc = empty($_POST["account"]) ? true : false;
    $fp = empty($_POST["password"]) ? true : false;
    if ($fc || $fp) {
        $errMsg =
            '<div class="alert alert-danger" role="alert">未輸入帳號或密碼!</div>';
    }
    //如果使用者都有輸入的話
    else {
        $account = test_input($_POST["account"]); //接收使用者所輸入之帳號，並做處理。
        $password = test_input($_POST["password"]);
        // $password = test_input($_POST['password']);//接收使用者所輸入之密碼，並做處理。
        include "connect.php";
        //預先寫好要查詢的欄位，並用prepare語法避免sql injection並加快系統運作。
        $sql = "SELECT u_password FROM USERS WHERE u_account= :u_account ;";
        $stmt = $conn->prepare($sql);
        //使用bindParam()含式綁定一個參數到指定的變量，下列將使用者輸入的參數輸入綁定至sql，後即用execute()執行查詢。
        $stmt->bindParam(":u_account", $account);
        //執行查詢已加密密碼的sql
        $stmt->execute();
        //用columnCount()查看返回行數，如有返回一行即有資料。
        if ($stmt->rowCount() == 1) {
            //將查詢的結果指派給$row, 如果想要擷取多行，用fetchAll()
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            //如果密碼確認成功的話即給SESSION，代表已經登入了。
            if (password_verify($password, $row["u_password"])) {
                $_SESSION["u_account"] = $account;
                header("Location:member.php");
            }
            //如果密碼錯誤的話，顯示錯誤資訊。
            else {
                $errMsg =
                    '<div class="alert alert-danger fw-bold" role="alert">帳號或密碼錯誤，請重新輸入!</div>';
            }
        }
        //沒找到密碼的話就代表沒這個帳號，顯示錯誤訊息。
        else {
            $errMsg =
                '<div class="alert alert-danger fw-bold" role="alert">帳號或密碼錯誤，請重新輸入!</div>';
        }
        //資料庫連線完後關閉
        $conn = null;
        $stmt = null;
    }
}
?>

    <div class="container">
      <div class="row vh-100 align-items-center justify-content-center">
        <div class="card mb-3" style="max-width: 580px;">
          <div class="row g-0">
            <div class="col-md-4">
              <a href="index.html" >
                <img src="img/pekora.png" class="img-fluid rounded-start" alt="這裡是圖片a，圖片去哪了，路徑趕快找" data-bs-toggle="tooltip" data-bs-placement="top"
                data-bs-custom-class="custom-tooltip"
                data-bs-title="Pardon? 點我回首頁peko!">
              </a>
              <a href="regist.php" class="card-text text-decoration-none"><small class="text-muted fw-bold">沒有帳號?按我註冊</small></a>
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title fw-bold fs-3">會員系統</h5>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="account" name="account" required>
                    <label for="floatingInput">帳號</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPassword"  name="password"placeholder="Password"  required>
                    <label for="floatingPassword">密碼</label>
                  </div>
                  <div class="row"><button type="submit" class="btn btn-primary w-75 mx-auto" name="submit">登入</button>
                  </div>
                </form>
              </div>
            </div>
            <?php
            //顯示錯誤資訊
            $errMsg = $errMsg ?? '';
            echo $errMsg;
            ?>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
  </body>
</html>