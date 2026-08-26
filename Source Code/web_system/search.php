<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>簡易搜尋</title>
        <!-- 使用bootstrap模板美化，如果你想用原生css，就把下面的link與script刪掉 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- 這是最上方導覽列 -->
        <?php include('navbar.html')  ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="text-center">
                        <h2>簡易搜尋</h2><br />
                        <form class="form-inline justify-content-center" method="GET" action="search.php">
                            <input class="form-control mr-sm-2" name="keyword" type="text" placeholder="請輸入書名" size="40" />
                            <button class="btn btn-outline-info my-2" type="submit">查詢書名</button>
                        </form>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">編號</th>
                                    <th scope="col">書名</th>
                                    <th scope="col">作者</th>
                                    <th scope="col">價格</th>
                                    <th scope="col">出版商</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //取得html input form裡面使用者輸入的書名，<input name="keyword">
                                $keyword = $_GET["keyword"] ?? "";

                                //連接資料庫
                                include "connect.php";
                                //使用sql語法"LIKE"搜尋使用者輸入的文字
                                $sql = "SELECT * FROM books WHERE b_bookname LIKE '%$keyword%' ORDER BY b_no";
                                //pdo準備
                                $exec = $conn->prepare($sql);
                                //sql執行
                                $exec->execute();

                                //用loop帶出資料，$exec->fetch()取得一行後即存入$row中，再使用html語法列印出來
                                while ($row = $exec->fetch()) {
                                    echo "<tr>";
                                    echo "<th>" . $row["b_no"] . "</th>";
                                    echo '<td class="fw-bold fs-5">' . $row["b_bookname"] . "</td>";
                                    echo "<td>" . $row["b_author"] . "</td>";
                                    echo "<td>" . $row["b_price"] . "</td>";
                                    echo "<td>" . $row["b_publisher"] . "</td>";
                                    echo "</tr>";
                                }
                                $conn = null;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
</html>