
<?php
/*// Подключение к базе данных
$servername = "localhost";
$username = "ваше_имя_пользователя";
$password = "ваш_пароль";
$dbname = "ваша_база_данных";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Ошибка подключения: " . $conn->connect_error);
}

// Регистрация пользователя
if (isset($_POST['register'])) {
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
if ($conn->query($sql) === TRUE) {
echo "Пользователь успешно зарегистрирован.";
} else {
echo "Ошибка: " . $sql . "<br>" . $conn->error;
}
}

// Авторизация пользователя
if (isset($_POST['login'])) {
$username = $_POST['login_username'];
$password = $_POST['login_password'];

$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
$row = $result->fetch_assoc();
if (password_verify($password, $row['password'])) {
echo "Вход выполнен успешно.";
} else {
echo "Неправильный пароль.";
}
} else {
echo "Пользователь не найден.";
}
}

// Забыли пароль
if (isset($_POST['forgot_password'])) {
$username = $_POST['forgot_username'];

$sql = "SELECT password FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
$row = $result->fetch_assoc();
echo "Ваш пароль: " . $row['password'];
} else {
echo "Пользователь не найден.";
}
}

$conn->close();
*/?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.css">
    <link rel="stylesheet" href="css/bootstrap-utilities.css">
    <link rel="stylesheet" href="css/custom/auth.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>

<nav class="container container-fluid" id="button-container">
    <button id="return-back-btn" onclick="window.history.back()">
        <img src="img/backarrow.svg" alt="назад" width="40">
    </button>
    <div id="head">
        <h1>
            Войти
        </h1>
    </div>
    <div id="head-gap">
    </div>
</nav>
<div id="main-wrap" class="container-fluid container">
    <form id="login-form">
        <div class="form-group">
            <label for="login-phone">Номер телефона:</label>
            <input type="tel" id="login-phone" class="form-control" placeholder="+7 (___) ___ ____" required>
        </div>
        <div class="form-group">
            <label for="login-password">Пароль:</label>
            <div class="input-group" id="password-wrap">
                <input type="password" id="login-password" class="form-control" placeholder="Введите пароль" required>
                <span class="input-group-addon">
            <button type="button" id="show-password">
              <img src="img/hide_password_eye_icon.svg" alt="показать пароль" width="20" id="eye-svg"> </button>
             </span>
            </div>
        </div>
        <div class="form-group" id="form-end">
            <a href="#" id="forgot-password">Забыли пароль?</a>
            <button type="submit" class="btn btn-primary btn-block" id="login-btn">Войти</button>
        </div>
    </form>
</div>

<script>
    let checked = 0
    document.getElementById("show-password").addEventListener("click", function () {
        let passwordField = document.getElementById("login-password");
        let svg = document.getElementById("eye-svg")
        if (checked === 0) {
            passwordField.type = "text"; // Отобразить пароль
            svg.attributes.getNamedItem("src").value = "show_password_eye.svg"
            checked = 1
        } else {
            passwordField.type = "password"; // Скрыть пароль
            svg.attributes.getNamedItem("src").value = "hide_password_eye_icon.svg"
            checked = 0
        }
    });
</script>
</body>
</html>