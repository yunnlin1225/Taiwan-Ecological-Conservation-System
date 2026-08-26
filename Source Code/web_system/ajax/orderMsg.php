<?php
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//接收傳來的資料並驗證
$order = test_input($_GET["order"]);


//如果傳來的是ASC或是undefined，就升序排列
if ($order == "ASC" || $order == "undefined") {
    include "../connect.php";
    if (isset($conn)) {
        $sql = "SELECT * FROM messages ORDER BY m_date ASC";
        $exec = $conn->prepare($sql);
        $exec->execute();

        //使用Loop帶出資料
        while ($row = $exec->fetch()) {
            $m_name = $row["m_name"];
            $m_content = $row["m_content"];
            $datetime = date("h:i:s a Y-m-d", strtotime($row["m_date"]));

            // 將select出的資料鑲嵌在html中，board.php將會收到echo 出來的東西並顯示
            echo "<li class=\"list-group-item d-flex justify-content-between align-items-start mt-2\">
					<div class=\"ms-2 me-auto\">
						<div class=\"fw-bold\">$m_name</div>$m_content</div>
						<span class=\"badge bg-secondary rounded-pill\">$datetime</span>
				</li>";
        }
        $conn = null;
    } else {
        exit();
    }
}

//如果傳來的是DESC，就降序排列
if ($order == "DESC") {
    include "../connect.php";
    if (isset($conn)) {
        $sql = "SELECT * FROM messages ORDER BY m_date DESC";
        $exec = $conn->prepare($sql);
        $exec->execute();

        //使用Loop帶出資料
        while ($row = $exec->fetch()) {
            $m_name = $row["m_name"];
            $m_content = $row["m_content"];
            $datetime = date("h:i:s a Y-m-d", strtotime($row["m_date"]));

            // 將select出的資料鑲嵌在html中，board.php將會收到echo 出來的東西並顯示
            echo "<li class=\"list-group-item d-flex justify-content-between align-items-start mt-2\">
					<div class=\"ms-2 me-auto\">
						<div class=\"fw-bold\">$m_name</div>$m_content</div>
						<span class=\"badge bg-secondary rounded-pill\">$datetime</span>
				</li>";
        }
        $conn = null;
    } else {
        exit();
    }
}
?>
