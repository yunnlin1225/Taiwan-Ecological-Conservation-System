<?php
function fetchProtectedAnimals() {
    include('connect.php');
    
    // 根據篩選條件來查詢資料
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all'; // 取得篩選條件，預設為 'all'
    
    // 根據篩選條件組合 SQL 查詢
    if ($filter == 'all') {
        $sql = 'SELECT * FROM protected_animals ORDER BY animal_id'; // 查詢所有動物
    } else {
        $sql = 'SELECT * FROM protected_animals WHERE animal_type = :filter ORDER BY animal_id'; // 根據保護狀況篩選
    }

    $exec = $conn->prepare($sql);

    // 如果有篩選條件，則綁定篩選參數
    if ($filter != 'all') {
        $exec->bindParam(':filter', $filter, PDO::PARAM_STR);
    }

    $exec->execute();

    $data = array();
    $i = 0;

    // 將資料打包成物件格式
    while ($row = $exec->fetch()) {
        $data[$i]['animal_id'] = $row['animal_id'];           // 動物編號
        $data[$i]['animal_name'] = $row['animal_name'];       // 動物名稱
        $data[$i]['animal_type'] = $row['animal_type'];       // 動物類別（珍貴稀有、瀕臨絕種等）
        $data[$i]['habitat'] = $row['habitat'];               // 棲息地
        $data[$i]['altitude_level'] = $row['altitude_level']; // 海拔高度
        $i++;
    }

    // 關閉資料庫連線
    $conn = null;

    // 返回 JSON 格式的資料
    return json_encode($data);
}

// 輸出資料
echo fetchProtectedAnimals();
?>
