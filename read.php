<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT * FROM devices";
    if ($searchQuery) {
        $sql .= "WHERE device_type LIKE '%$searchQuery%'
                            OR ID LIKE '%$searchQuery%' 
                            OR manufacturer LIKE '%$searchQuery%'
                            OR model LIKE '%$searchQuery%'
                            OR serial_number LIKE '%$searchQuery%'
                            OR purchase_date LIKE '%$searchQuery%'";
    }

    $result = $mysqli->query($sql);

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

