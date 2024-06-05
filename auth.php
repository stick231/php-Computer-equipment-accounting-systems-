<?php
require_once "db.php";
session_start();

if (isset($_POST["login"]) && isset($_POST["password"])) {
    $login = $_POST["login"];
    $password = $_POST["password"];


    $query = "SELECT * FROM users WHERE login = '$login'";
    $request = $conn->query($query);

    if ($request) {
        $row = mysqli_fetch_assoc($request);

        if ($row !== null && $login == $row["login"] && $password == $row["password"]) {
            $_SESSION["login"] = $login;
            $_SESSION["password"] = $password;
            header("location: index.html");
            exit;
        } else {
            $warning = "Неверный логин или пароль";
        }
    } else {
        $warning = "Пользователь не найден";
    }
} else {
    $warning = "Ошибка авторизации";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_error.css">
    <title>Ошибка входа</title>
</head>
<body>
    <div>
        <h2>Произошла ошибка</h2>
        <p><?php echo  $warning ?></p>
        <button>Попробовать снова</button>
    </div>
    <script>
        const button = document.querySelector("button")
        button.addEventListener("click", function(){
            window.location.href = "login.php";
        })
    </script>
</body>
</html>
