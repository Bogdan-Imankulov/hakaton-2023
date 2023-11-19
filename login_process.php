<?php
session_start();

$servername = "localhost";
$username = "p-338798_admin";
$password = "p-338798_admin";
$dbname = "p-338798_admin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tel = $conn->real_escape_string($_POST['tel']);
$password = $conn->real_escape_string($_POST['password']);

// Получаем хэш пароля из базы данных для введенного номера телефона
$get_user_sql = "SELECT * FROM users WHERE tel='$tel'";
$get_user_result = $conn->query($get_user_sql);

if ($get_user_result->num_rows > 0) {
    $row = $get_user_result->fetch_assoc();
    $stored_password = $row['password'];

    // Проверяем совпадение введенного пароля и хэша из базы данных
    if (password_verify($password, $stored_password)) {
        // Пароль верный, пользователь успешно авторизован

        // Устанавливаем данные пользователя в сессию
        $_SESSION['tel'] = $tel;
        $_SESSION['authenticated'] = true;
        $_SESSION['expiry_time'] = time() + 1200; // 1200 секунд (20 минут) с момента авторизации

        // Генерируем уникальный идентификатор сессии и сохраняем его в базе данных
        $session_id = session_id();
        $update_session_sql = "UPDATE users SET session_id = '$session_id' WHERE tel = '$tel'";
        $conn->query($update_session_sql);

        // Перенаправление на страницу main.php
        header("Location: main.php");
        exit();
    } else {
        // Ошибка авторизации
        $error_message = "Неправильный пароль. Попробуйте еще раз.";
    }
} else {
    // Ошибка авторизации
    $error_message = "Пользователь с указанным номером телефона не найден.";
}

// Закрытие соединения с базой данных
$conn->close();

// Перенаправление на страницу login.php с сообщением об ошибке
header("Location: login.php?error=" . urlencode($error_message));
exit();
?>
