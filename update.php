<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deviceType = $_POST['device_type'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $serialNumber = $_POST['serial_number'];
    $purchaseDate = $_POST['purchase_date'];
    $id = $_POST['id'];

    $query = "UPDATE devices SET device_type = ?, manufacturer = ?, model = ?, serial_number = ?, purchase_date = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssssi", $deviceType, $manufacturer, $model, $serialNumber, $purchaseDate, $id);

    if ($stmt->execute()) {
        $response = array(
            'success' => true,
            'message' => 'Устройство успешно обновлено'
        );
        echo json_encode($response);
    } else {
        $response = array(
            'success' => false,
            'message' => 'Ошибка при обновлении устройства: ' . $stmt->error
        );
        echo json_encode($response);
    }

    $stmt->close();
}
?>