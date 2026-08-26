<?php
include('connect.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search !== '') {
    $sql = "SELECT * FROM books WHERE animal_name LIKE :search ORDER BY animal_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':search', '%' . $search . '%');
} else {
    $sql = "SELECT * FROM books ORDER BY animal_id";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);
?>
