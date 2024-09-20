<?php

namespace CustomTable;

class ColorCustom{
    public function saveColors()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $colorBorder = $data['color_border'] ?? null;
        $colorText = $data['color_text'] ?? null;
        $colorBackground = $data['color_background'] ?? null;
    
        if ($colorBorder === null || $colorText === null || $colorBackground === null) {
            http_response_code(400);
            echo json_encode(array("message" => "Ошибка: Данные не предоставлены"));
            exit;
        }
    
        session_start();
        $_SESSION["color_border"] = $colorBorder;
        $_SESSION["color_text"] = $colorText;
        $_SESSION["color_background"] = $colorBackground;
    
        http_response_code(201); 
        echo json_encode(array("message" => "Цвета сохранены успешно", 
                               "color_border" => $colorBorder, 
                               "color_text" => $colorText, 
                               "color_background" => $colorBackground));
        exit;
    }

    public function getColor()
    {
        if(isset($_SESSION["color_border"]) && isset($_SESSION["color_text"]) && isset($_SESSION["color_background"])){
            http_response_code(200); // 200 OK
            echo json_encode(array("color_border" => $_SESSION["color_border"], "color_text" => $_SESSION["color_text"], "color_background" => $_SESSION["color_background"]));
            exit;
        }
        echo json_encode(array("message" => "цвета не были введены"));
        exit;
    }
}