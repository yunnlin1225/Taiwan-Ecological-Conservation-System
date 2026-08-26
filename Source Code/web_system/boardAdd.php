<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btn"])) {
    include "connect.php";
    // convert(varchar, getdate(), 120) => 使用MSSQL內建函數convert(型態, 日期, 格式->120是yyyy-mm-dd hh:mi:ss)新增日期進去
    // $sql = "INSERT INTO messages(m_name, m_content, m_date) VALUES (:m_name, :m_content, convert(varchar, getdate(), 120))";
    $sql = "INSERT INTO messages(m_name, m_content, m_date) VALUES (:m_name, :m_content, now())";
    $exec = $conn->prepare($sql);
    //將$_POST['m_name']代入至:m_name ; $_POST['m_name']是接收board.php <form>裡面name=m_name的<input>資料
    $exec->bindParam(":m_name", $_POST["m_name"]);
    //將$_POST['m_content']代入至:m_content ; $_POST['m_content']接收board.php <form>裡面name=m_content的<textarea>資料
    $exec->bindParam(":m_content", $_POST["m_content"]);
    $result = $exec->execute(); //執行insert語法
    if ($result) {
        header("Location:board.php");
    }
    //跳轉回board.php
    else {
        echo "error";
    }
    $conn = null;
}
?>
