<?php
session_start();

if (isset($_SESSION["register"]) && $_SESSION["register"] !== true) {
    echo 'false';
} elseif (!isset($_SESSION["login"]) && !isset($_SESSION["password"])) {
    echo 'true';
} else {
    echo 'none';
}
?>