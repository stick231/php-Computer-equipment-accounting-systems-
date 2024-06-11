<?php
session_start();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if(isset($data["color_border"]) && isset($data["color_text"]) && isset($data["color_background"])){
        $_SESSION["color_border"] = $data["color_border"];
        $_SESSION["color_text"] = $data["color_text"];
        $_SESSION["color_background"] = $data["color_background"];
        http_response_code(201); // 201 Created
        echo json_encode(array("message" => "Цвета сохранены успешно"));
        exit;
    } else {
        http_response_code(400); // 400 Bad Request
        echo json_encode(array("message" => "Ошибка: Данные не предоставлены"));
        exit;
    }
}
if($_SERVER["REQUEST_METHOD"] === "GET"){
    if(isset($_SESSION["color_border"]) && isset($_SESSION["color_text"]) && isset($_SESSION["color_background"])){
        http_response_code(200); // 200 OK
        echo json_encode(array("color_border" => $_SESSION["color_border"], "color_text" => $_SESSION["color_text"], "color_background" => $_SESSION["color_background"]));
        exit;
    }
    echo json_encode(array("message" => "цвета не были введены"));
    exit;
}
?>