<?php
require_once "db.php";
session_start();

if (isset($_POST["login"]) && isset($_POST["password"])) {
    $login = $_POST["login"];
    $password = $_POST["password"];

    if (empty($login) || empty($password)) {
        $warning = "Поля логин и пароль должны быть заполнены";
    } else {
        $query = "SELECT COUNT(*) FROM users WHERE login = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $warning = "Такой пользователь уже есть";
        } else {
            $query = "INSERT INTO users (login, password) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ss", $login, $hashed_password);
            $stmt->execute();
            $stmt->close();

            $userId = $conn->insert_id;
            setcookie("user_idT", $userId, time() + 3600 * 24 * 30, "/"); 
            $_SESSION['user_idT'] = $userId;

            header("location: index.html");
            exit;
        }
    }
} else {
    $warning = "Ошибка регистрации";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_error.css">
    <title>Ошибка регистрации</title>
</head>
<body>
    <div>
        <h2>Произошла ошибка</h2>
        <p><?php echo $warning ?></p>
        <button>Попробовать снова</button>
    </div>
    <script>
        const button = document.querySelector("button")
        button.addEventListener("click", function(){
            window.location.href = "register.php";
        })
    </script>
</body>
</html>