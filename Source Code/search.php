<!doctype html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>生態動物搜尋</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
</head>
<body>
    <!-- 導覽列 -->
    <?php include('navbar.html') ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="text-center">
                    <h2 class="mb-4">動物搜尋系統</h2>

                    <!-- 搜尋表單 -->
                    <form class="form-inline justify-content-center mb-4" method="GET" action="search.php">
                        <input class="form-control me-2" name="keyword" type="text" placeholder="請輸入動物名稱" size="40" />
                        <button class="btn btn-outline-success" type="submit">查詢動物</button>
                    </form>

                    <!-- 結果表格 -->
                    <table class="table table-bordered table-striped">
                        <thead class="table-success">
                            <tr>
                                <th scope="col">編號</th>
                                <th scope="col">動物名稱</th>
                                <th scope="col">分類</th>
                                <th scope="col">棲息地</th>
                                <th scope="col">描述</th>
                                <th scope="col">海拔</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $keyword = $_GET["keyword"] ?? "";

                            try {
                                include "connect.php"; // 連接資料庫，$conn 是 PDO 物件

                                $sql = "SELECT * FROM search WHERE name LIKE :keyword ORDER BY id";
                                $stmt = $conn->prepare($sql);

                                $likeKeyword = '%' . $keyword . '%';
                                $stmt->execute(['keyword' => $likeKeyword]);

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<th>" . htmlspecialchars($row["id"]) . "</th>";
                                    echo '<td class="fw-bold fs-5">' . htmlspecialchars($row["name"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["species_type"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["region"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["status"]) . "</td>";

                                    // 海拔判斷改成 altitude_level
                                    $altitudeRaw = isset($row["altitude_level"]) ? trim($row["altitude_level"]) : "";
                                    if ($altitudeRaw === "") {
                                        $altitudeDisplay = "無資料";
                                        $color = "gray";
                                    } else {
                                        $altitudeDisplay = htmlspecialchars($altitudeRaw);
                                        switch ($altitudeRaw) {
                                            case "低":
                                                $color = "green";
                                                break;
                                            case "中":
                                                $color = "orange";
                                                break;
                                            case "高":
                                                $color = "red";
                                                break;
                                            default:
                                                $color = "gray";
                                                break;
                                        }
                                    }
                                    echo "<td style='color: {$color}; font-weight:bold;'>{$altitudeDisplay}</td>";

                                    echo "</tr>";
                                }

                                $conn = null;
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='6' class='text-danger'>資料庫錯誤：" . htmlspecialchars($e->getMessage()) . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>