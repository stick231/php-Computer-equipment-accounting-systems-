<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $deviceType = $_POST['device_type'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $serialNumber = $_POST['serial_number'];
    $purchaseDate = $_POST['purchase_date'];

    $query = "INSERT INTO devices (device_type, manufacturer, model, serial_number, purchase_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssss", $deviceType, $manufacturer, $model, $serialNumber, $purchaseDate);
    $stmt->execute();

    $response = array(
        'success' => true,
        'message' => 'Устройство успешно создано'
    );
    echo json_encode($response);
}
