<?php
session_start();

if (isset($_SESSION["login"]) && isset($_SESSION["password"]) == true) {
  header("location: index.html");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Логин</title>
  <link rel="stylesheet" href="style_formUser.css">
</head>
<body>
<form action="auth.php" method="post" id="myForm">
    <h1>Войти в аккаунт</h1>
    <div class="form__input">
        <input type="text" name="login" id="login" maxlength="15">
        <label class="form__label" for="login">Логин:</label>
    </div>
    <div class="form__input">
        <input type="password" name="password" id="password" required>
        <label class="form__label" for="password">Пароль:</label>
    </div>
    <input type="submit" id="button" value="Войти">
</form>
  <script>
    const loginInput = document.querySelector('input[name="login"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const inputSubmit = document.querySelector('input[type="submit"]');
    inputSubmit.addEventListener('click', (e) => {
      e.preventDefault();
      const form = document.getElementById('myForm');
      const loginValue = loginInput.value.trim();
      const passwordValue = passwordInput.value.trim();

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