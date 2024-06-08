<?php
require_once 'db.php';
session_start();

if (isset($_POST["login"]) && isset($_POST["password"])) {
    $login = $_POST["login"];
    $password = $_POST["password"];

    $query = "SELECT COUNT(*) FROM users WHERE login = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if($count > 0){
        $_SESSION['error_message'] = 'Такой пользователь уже есть';
        header("location: register.php");
        exit;
    }   
    else{
        $query = "INSERT INTO users (login, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $login, $password);
        $stmt->execute();
        $stmt->close();
        $_SESSION["register"] = true;
        header("location: index.html");
        exit;
    }
}
?>