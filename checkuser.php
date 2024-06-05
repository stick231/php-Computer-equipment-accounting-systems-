<?php 
session_start();

if(isset($_SESSION["register"]) && $_SESSION["register"] !== true){
   echo "true";
}
elseif(!isset($_SESSION["login"]) && !isset($_SESSION["password"])){
    echo 'false';
}
?>