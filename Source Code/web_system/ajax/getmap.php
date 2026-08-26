<?php
//將此網頁輸出改為json格式
header("Content-Type: application/json; charset=utf-8");

//如果使用者送出GET訊號
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    //指派GET取得的數值給變數，分別有城市、地區、禮拜幾休息、查詢營業時間
    $city = $_GET["city"] ?? "";
}
$area = $_GET["area"] ?? "";
$sleep = $_GET["sleep"] ?? "";
$time = $_GET["time"] ?? "";

include "../connect.php";
if (isset($conn)) {
    $sql = "SELECT * FROM bookstore WHERE BK_ADDRESS LIKE '%$city%' AND BK_ADDRESS LIKE '%$area%' AND BK_SLEEP != '$sleep' ";
    if (!empty($time)) {
        $sql .= " AND '$time' BETWEEN BK_START AND BK_END";
    }
    $exec = $conn->prepare($sql);
    $exec->execute();
    $i = 1;

    //$html變數儲存查詢到的書店table
    $html = "";
    //$mapData變數儲存查詢到的書店資訊(經緯度等等..)
    $mapData = [];

    //loop取得sql查詢到的值
    while ($row = $exec->fetch()) {
        //將$sql取得的值分別指派給變數，這樣比較好做後續操作。
        $store = $row["bk_name"];
        $address = $row["bk_address"];
        $bk_start = $row["bk_start"];
        $bk_end = $row["bk_end"];
        $bk_la = $row["bk_latitude"];
        $bk_lo = $row["bk_longitude"];

        //將每次拿到的商店名字、地址、經度、緯度存成array的形式。
        $loopArr = [$store, $address, $bk_la, $bk_lo];
        //將上面的array存到$mapData地圖資訊中，在loop過程中，mapData慢慢會把全部存完。
        $mapData[$i - 1] = $loopArr;

        //將要印出的HTML串列在一起。
        $html .= "<tr>
                <th scope=\"row\">{$i}</th>
                <td>{$store}</td>
                <td>{$address}</td>
                <td>{$bk_start} ~ {$bk_end}</td>
              </tr>";
        $i++;
    }
    $conn = null;

    //將$html資訊與$mapData地圖資訊合成一個associate array，再轉成json格式。
    $arrAll = json_encode(
        ["html" => $html, "mapData" => $mapData],
        JSON_UNESCAPED_UNICODE
    );
    echo $arrAll;
}
?>
