<?php
require_once 'db.php';

if (isset($_GET["id"])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM devices WHERE id = ?";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $id);

    $stmt->execute();
    
    $stmt->close();
}
?>