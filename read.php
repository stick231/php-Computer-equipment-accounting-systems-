<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $query = "SELECT * FROM devices";
    $result = $mysqli->query($query);

    if (!$result) {
        echo "Ошибка выполнения запроса: " . $mysqli->error;
        exit;
    }

    $devices = array();
    while ($row = $result->fetch_assoc()) {
        $devices[] = $row;
    }

    echo json_encode($devices);
}
?>

