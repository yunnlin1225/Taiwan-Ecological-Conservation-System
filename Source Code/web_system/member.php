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
         //將$_SESSION['u_account']變數提出存放於$u_account
         $u_account = $_SESSION["u_account"];
         $sql = "SELECT * FROM users WHERE u_account = :u_account ";
         $stmt = $conn->prepare($sql);
         //將u_account代入至:u_account
         $stmt->bindParam(":u_account", $u_account);
         $stmt->execute(); //執行select語法
         $row = $stmt->fetch(); //抓值存放$row陣列中
        ?>
        <div class="container">
            <div class="row  vh-100 w-100 justify-content-center align-items-center">
                <div class="col">
                    <div class="card mx-auto border border-4 border-secondary" style="width: 18rem;">
                        <img src="img/peko_christ.png" class="card-img-top border border-5" alt="...">
                        <div class="card-body">
                            <h5 class="card-title fw-bold fs-3"><?php echo $row['u_name'] ?? '尚未登入 無法顯示';
                            //取出row內的姓名，並放入html裡?></h5>
                            <h6 class="card-subtitle mb-2 text-muted fw-bold fs-5"><?php echo $row['u_account'] ?? '尚未登入'; //取出row內的姓名，並放入html裡?></h6>
                            <p class="card-text fw-semibold fs-6"><?php echo $row['u_address'] ?? '尚未登入'; //取出row內的姓名，並放入html裡?></p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><a href="update.php" class="btn btn-primary w-100">修改</a></li>
                                <li class="list-group-item"><a href="login.php" class="btn btn-secondary w-100">返回</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
</html>