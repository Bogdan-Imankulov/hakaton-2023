<?php

session_start();

//// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || time() > $_SESSION['expiry_time']) {
    // Пользователь не авторизован или сессия истекла, перенаправляем на страницу авторизации
    header("Location: login.php");
    exit();
}

//// Подключение к базе данных (замените значения на ваши)
$servername = "localhost";
$username = "p-338798_admin";
$password = "p-338798_admin";
$dbname = "p-338798_admin";

$conn = new mysqli($servername, $username, $password, $dbname);

//// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//// Получаем баланс из базы данных для текущего пользователя
if (isset($_SESSION['tel'])) {
    $tel = $_SESSION['tel'];

    $sql = "SELECT balance FROM users WHERE tel = '$tel'";
    $result = $conn->query($sql);

    if ($result === false) {
        // Выводим сообщение об ошибке SQL
        echo "Error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            // Выводим данные каждой строки
            while ($row = $result->fetch_assoc()) {
                $user_balance = $row["balance"];
            }
        } else {
            echo "0 results";
        }
    }
} else {
    echo "User telephone not found in session.";
}


// Обработка запроса на получение данных о получателе
if (isset($_POST['getReceiverInfo'])) {
    $receiver_tel = $_POST['getReceiverInfo'];
    
    $sql = "SELECT first_name, last_name FROM users WHERE tel = '$receiver_tel'";
    $result = $conn->query($sql);

    if ($result === false) {
        // Выводим сообщение об ошибке SQL
        echo json_encode(array('error' => $conn->error));
        exit();
    } else {
        if ($result->num_rows > 0) {
            // Выводим данные каждой строки
            $row = $result->fetch_assoc();
            $receiver_first_name = $row["first_name"];
            $receiver_last_name = $row["last_name"];

            // Возвращаем данные в формате JSON
            echo json_encode(array('first_name' => $receiver_first_name, 'last_name' => $receiver_last_name));
            exit();
        } else {
            // Если пользователь не найден
            echo json_encode(array('error' => 'Получатель не найден'));
            exit();
        }
    }
}



// ... (ваш существующий PHP-код)

// Обработка запроса на получение данных о получателе
if (isset($_GET['tel'])) {
    $receiver_tel = $_GET['tel'];

    $sql = "SELECT first_name, last_name FROM users WHERE tel = '$receiver_tel'";
    $result = $conn->query($sql);

    if ($result === false) {
        // Выводим сообщение об ошибке SQL
        echo json_encode(array('error' => $conn->error));
        exit();
    } else {
        if ($result->num_rows > 0) {
            // Выводим данные каждой строки
            $row = $result->fetch_assoc();
            $receiver_first_name = $row["first_name"];
            $receiver_last_name = $row["last_name"];

            // Возвращаем данные в формате JSON
            echo json_encode(array('first_name' => $receiver_first_name, 'last_name' => $receiver_last_name));
            exit();
        } else {
            // Если пользователь не найден
            echo json_encode(array('error' => 'Получатель не найден'));
            exit();
        }
    }
}
?>