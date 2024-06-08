<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $sql = "SELECT * FROM devices LIMIT 5";
    $result = $mysqli->query($sql);
    $devices = array();
    
    while ($row = $result->fetch_assoc()) {
        $devices[] = $row;
    }
    echo json_encode($devices);
}
?>