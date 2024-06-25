<?php
session_start();
header('Content-Type: application/json');

if (isset($_COOKIE['user_idT'])) { 
    if(isset($_SESSION['login_userT'])){
        echo json_encode(["register" => true, "authentication" => true, "login" => $_SESSION['login_userT']]);
        exit;
    }
    elseif(!isset($_SESSION['user_idT'])){
        echo json_encode(['register' => true, 'authentication' => false]);
        exit;
    }
} 
else {
    echo json_encode(['register' => false, 'authentication' => false]);
    exit;
}