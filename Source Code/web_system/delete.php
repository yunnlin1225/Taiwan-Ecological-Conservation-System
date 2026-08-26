<?php
	include 'connect.php';
	$sql = "SELECT * FROM users";
	$result = $conn->query($sql);
	while($row = $result->fetch()){
		echo $row['u_account'] . '<br>';
	}
?>
