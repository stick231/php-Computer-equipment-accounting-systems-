<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\AuthController;
use Entities\User;
use Entities\Database;
use Repository\UserRepository;

$authAction = new AuthController();

setcookie("register", 'false', time() + 3600 * 24 * 30, "/");

if (isset($_SESSION["login"])) {
    $authAction->redirectToHomePage();
}

if (isset($_SESSION['auth_error'])) {
    $response = $_SESSION['auth_error'];
    unset($_SESSION['auth_error']); 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["login"]) && isset($_POST["password"])) {
        $database = new Database();
        $login = $_POST["login"];
        $password = $_POST["password"];
    
        $user = (new User())
            ->setUsername($login)
            ->setPassword($password);
    
        $userRepository = new UserRepository($database);
    
        if (!is_string($userRepository->authenticate($user))) {
            $authAction->redirectToHomePage();
        } else {
            $response = $userRepository->authenticate($user);
            $_SESSION['auth_error'] = $response;

            header("Location: /auth");
            exit; 
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Логин</title>
  <link rel="stylesheet" href="style_formUser.css">
</head>
<body>
<form method="post" id="myForm">
    <h1>Войти в аккаунт</h1>
    <?php if(isset($response)){echo '<p>'. $response . '</p>';}?>
    <div class="form__input">
        <input type="text" name="login" id="login" maxlength="15">
        <label class="form__label" for="login">Логин:</label>
    </div>
    <div class="form__input">
        <input type="password" name="password" id="password" required>
        <label class="form__label" for="password">Пароль:</label>
    </div>
    <a id='link'>Еще не зарегистрировался</a>
    <input type="submit" id="submit" value="Войти">
</form>
  <script>
        document.getElementById("link").addEventListener("click", () => {
            fetch("/register?register=false", {
                method: "GET",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
            })
            .then(response => {
                if (response.ok) {
                    console.log("Перенаправление на регистрацию");
                    setTimeout(() => {
                        window.location = "/register";
                    }, 500);
                } else {
                    console.error("Ошибка при перенаправлении:", response.status);
                }
            })
            .catch(error => {
                console.error("Ошибка при выполнении запроса:", error);
            });
        });

        function CheckInp(){
            const inpUsername = document.getElementById("username");
            const inpPassword = document.getElementById("password");
    

            if(inpUsername.value == null || inpUsername.value == ""){
                alert("Введите имя пользователя")
                return false;
            }

            if(inpPassword.value == null || inpPassword.value == ""){
                alert("Введите пароль")
                return false;
            }

            return true;
        }

        document.getElementById("submit").addEventListener("click", () =>{
            if (!CheckInp()) {
                event.preventDefault();
            }
        })
  </script>
</body>
</html>