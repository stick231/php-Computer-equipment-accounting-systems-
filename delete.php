<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deviceId = $_POST['id'];

    $query = "DELETE FROM devices WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $deviceId);

    if ($stmt->execute()) {
        $response = array(
            'success' => true,
            'message' => 'Устройство успешно удалено'
        );
    } else {
        $response = array(
            'success' => false,
            'message' => 'Ошибка при удалении устройства: ' . $stmt->error
        );
    }

    $stmt->close();
    echo json_encode($response);
}
?>