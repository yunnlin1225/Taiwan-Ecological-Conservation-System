<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>留言板</title>
        <!-- 使用bootstrap模板美化，如果你想用原生css，就把下面的link刪掉 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <!-- 當網頁載入完成後會去觸發javascript之showUser方法，顯示留言資料 -->
    <body onload="showUser()">
        <!-- 這是最上方導覽列 -->
        <?php include('navbar.html')  ?>

        <!-- 注意! 此網頁請搭配boardAdd.php與ajax/orderMsg.php使用boardAdd.php用於新增留言，orderMsg.php用於顯示留言 -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="text-center">
                        <h2>留言板</h2>
                    </div>
                    <div class="cal26rd">
                        <h5 class="card-title mt-3 me-4 text-muted">寫些東西...</h5>
                        <div class="card-body">
                            <!-- 需要輸入的資訊都在form裡面，submit後會跳轉至 boardAdd.php 執行新增動作，請注意name名稱會對應php的$_POST[''] -->
                            <form method="POST" action="boardAdd.php">
                                <input class="form-control" name="m_name" placeholder="您的暱稱" size="40" maxlength="30" required="required" />
                                <br />
                                <textarea class="form-control" style="resize:none" name="m_content" placeholder="請留言..." rows="4" required="required"></textarea>
                                <br />
                                <div class="text-center">
                                    <input class="btn btn-outline-primary" type="submit" name="btn" value="送出留言" />
                                </div>
                            </form>
                            <!-- form 結束 -->
                            <hr class="fw-bold">
                            <div class="row justify-content-end">
                                <form>
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">

                                    <!-- 監聽按鈕onclick，當使用者按下後會去觸發showUser()，傳遞自己value內 -->
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" value="ASC" onclick="showUser(this.value)"checked>
                                    <label class="btn btn-outline-primary" for="btnradio1">由新至舊</label>

                                    <!-- 監聽按鈕onclick，當使用者按下後會去觸發showUser()，傳遞自己value內 -->
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" value="DESC" onclick="showUser(this.value)" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="btnradio2">由舊至新</label>
                                </div>
                                </form>
                            </div>
                            <!-- 使用ajax搭配ajax/orderMsg.php顯示資料，先把最外層html架構寫好，再利用ajax把內層顯示寫出來，使用Loop帶出資料-->
                            <ol class="list-group list-group-numbered overflow-auto" id="orderlist" style="height: 400px">

                                <!-- Loop end-->
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymousymous"></script>
        <script>
            function showUser(str) {
                document.getElementById("orderlist").innerHTML="<div class=\"spinner-border\" role=\"status\"><span class=\"visually-hidden\">Loading...</span></div>";
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("orderlist").innerHTML = this.responseText;
                  }
                };
                xmlhttp.open("GET","ajax/orderMsg.php?order="+str,true);
                xmlhttp.send();
            }
        </script>
    </body>
</html>