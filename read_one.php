<?php
require_once 'db.php';


if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $id = $_GET['id'];

    $sql = "SELECT * FROM devices WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $device = $result->fetch_assoc();
        echo json_encode($device);
    } else {
        echo json_encode(array("error" => "Устройство не найдено"));
    }
    $stmt->close();
}

?>
