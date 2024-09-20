<?php

namespace Controllers;

class AuthController{
    public function redirectToAuth()
    {
        include 'auth.php';
        exit;
    }

    public function redirectToRegister()
    {
        include 'register.php';
        exit;
    }
    
    public function redirectToHomePage()
    {
        header('Location: /');
    }

    public function checkUser() {
        if (isset($_COOKIE['user_id'])) { 
            if(isset($_SESSION['just_register'])){
                echo json_encode(["register" => true, "authentication" => true, "login" => $_SESSION['just_register']]);
                exit;
            }
            if(isset($_SESSION['login'])){
                echo json_encode(["register" => true, "authentication" => true, "login" => $_SESSION['login']]);
                exit;
            }
            elseif(!isset($_SESSION['user_id'])){
                echo json_encode(['register' => true, 'authentication' => false]);
                exit;
            } 
        }
        else {
            echo json_encode(['register' => false, 'authentication' => false]);
            exit;
        }
        exit;
    }

}