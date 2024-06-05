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
    <h1>Войти в аккаут</h1>
    <label>Логин: <input type="text" name="login" /></label>
    <label>Пароль: <input type="password" name="password" /></label>
    <input type="submit" id="button" value="войти" />
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