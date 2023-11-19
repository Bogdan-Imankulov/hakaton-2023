<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Подключение к базе данных (замените данными вашей базы данных)
    $servername = "localhost";
    $db_username = "p-338798_admin";
    $db_password = "p-338798_admin";
    $dbname = "p-338798_admin";
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Проверка подключения
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    // Защита от SQL-инъекций
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Поиск пользователя в базе данных
    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Пользователь найден
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;

        // Установка времени сессии (20 минут)
        $_SESSION["timeout"] = time() + 1200;

        // Перенаправление на admin.php
        header("location: admin.php");
    } else {
        // Неверные учетные данные
        $error = "Неверное имя пользователя или пароль";
    }

    // Закрытие подключения к базе данных
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Вход</h2>

<?php
if (isset($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="form-group">
        <label for="username">Имя пользователя:</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>

    <div class="form-group">
        <label for="password">Пароль:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <button type="submit" class="btn btn-primary">Войти</button>
</form>

</body>
</html>
