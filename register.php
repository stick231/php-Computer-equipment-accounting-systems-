<?php
session_start();

if (isset($_SESSION["register"]) && $_SESSION["register"] == true) {
    header("location: index.html");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_formUser.css">
    <title>Регистрация</title>
</head>
<body>
    <form action="complete.php" method="post" id="myForm">
        <h1>Регистрация</h1>
    <div class="form__input">
        <input type="text" name="login" id="login" maxlength="15">
        <label class="form__label" for="login">Логин:</label>
    </div>
    <div class="form__input">
        <input type="password" name="password" id="password" required>
        <label class="form__label" for="password">Пароль:</label>
    </div>
    <input type="submit" id="button" value="Зарегистрироваться">
    </form>
    <script>
        const loginInput = document.querySelector('input[name="login"]');
        loginInput.addEventListener('input', () => {
            const loginValue = loginInput.value.trim();
            if (loginValue.length < 3) {
                loginInput.setCustomValidity('Логин должен быть не менее 3 символов');
            } else {
                loginInput.setCustomValidity('');
            }
        });
        const passwordInput = document.querySelector('input[name="password"]');
        passwordInput.addEventListener('input', () => {
            const passwordValue = passwordInput.value.trim();
            if (passwordValue.length < 6) {
                passwordInput.setCustomValidity('Пароль должен быть не менее 6 символов');
            } else {
                passwordInput.setCustomValidity('');
            }
        });
        const inputSubmit = document.querySelector('input[type="submit"]');
        inputSubmit.addEventListener('click', (e) => {
            e.preventDefault();
            const form = document.getElementById('myForm');
            const loginValue = form.login.value.trim();
            const passwordValue = form.password.value.trim();

            if (loginValue === "") {
                alert('Введите логин');
                return;
            }
            if (passwordValue === "") {
                alert('Введите пароль');
                return;
            }

            form.submit();
        });
    </script>
</body>
</html>
