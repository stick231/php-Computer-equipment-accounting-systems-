<?php
$user = "root";
$pass = "";
$db1 = "users";
$db2 = "inventory";
$host = "localhost";

$conn = new mysqli($host, $user, $pass, $db1);
$mysqli = new mysqli($host, $user, $pass, $db2);

if($conn->connect_error){
    die('Ошибка подключения (' . $conn1->connect_errno . ') ' . $conn1->connect_error);
}

if($mysqli->connect_error){
    die('Ошибка подключения (' . $conn2->connect_errno . ') ' . $conn2->connect_error);
}
?>
      