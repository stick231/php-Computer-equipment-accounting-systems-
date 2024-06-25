<?php
require_once "db.php";
session_start();

if (isset($_POST["login"]) && isset($_POST["password"])) {
    $login = $_POST["login"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE login = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($password, $row["password"])) {
        $_SESSION["login_userT"] = $login;
        header("location: index.html");
        exit;
    } else {
        $warning = "Неверный логин или пароль";
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
