<?php
session_start();
$_SESSION['search_result'] = '';

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Check session timeout
if (time() > $_SESSION["timeout"]) {
    session_destroy();
    header("location: login.php");
    exit;
}

// Update session timeout
$_SESSION["timeout"] = time() + 1200;

$servername = "localhost";
$username = "p-338798_admin";
$password = "p-338798_admin";
$dbname = "p-338798_admin";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query
$tel = $_POST["phoneNumber"];
$sql = "SELECT * FROM users WHERE tel = '$tel'";
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch data and do something with it
    while ($row = $result->fetch_assoc()) {
        // Do something with the data, for example, echo it
        $userInfo['tel'] = $row['tel'];
        $userInfo['balance']= $row['balance'];
        $userInfo['institution_name'] = $row['institution_name'];
        $userInfo['first_name'] = $row['first_name'];
        $userInfo['last_name'] = $row['last_name'];
        $userInfo['card_number'] = $row['card_number'];
    }
} else {
    echo "no db connection";
}
$sql = "SELECT * FROM card_info WHERE user_tel = '$tel'";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Do something with the data, for example, echo it
        $userInfo['card_number'] = $row['card_number'];
    }
}
// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Админ панель</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Выйти</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Form for updating phone number -->
<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title">Обновить номер телефона</h5>
        <form method="post" action="">
            <div class="form-group">
                <label for="phoneNumber">Номер телефона:</label>
                <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber"
                       placeholder="Введите ваш номер телефона">
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<!-- Display user information -->
<div class="mt-3">
    <h3>Информация о пользователе:</h3>
    <ul>
        <li>Тел:<?php echo isset($userInfo['tel']) ? $userInfo['tel'] : 'N/A'; ?> </li>
        <li>Баланс: <?php echo isset($userInfo['balance']) ? $userInfo['balance'] : 'N/A'; ?></li>
        <li>Учебное
            заведение: <?php echo isset($userInfo['institution_name']) ? $userInfo['institution_name'] : 'N/A'; ?></li>
        <li>Имя: <?php echo isset($userInfo['first_name']) ? $userInfo['first_name'] : 'N/A'; ?></li>
        <li>Фамилия: <?php echo isset($userInfo['last_name']) ? $userInfo['last_name'] : 'N/A'; ?></li>
        <li>Номер карты: <?php echo isset($userInfo['card_number']) ? $userInfo['card_number'] : 'N/A'; ?></li>
    </ul>
</div>

<div>
    <?php
    $db = new mysqli('localhost', 'p-338798_admin', 'p-338798_admin', 'p-338798_admin');

    // Получение вопросов и ответов из базы данных
    $result = $db->query("SELECT * FROM admin_question");
    if ($result === false) {
        echo '';
    } else {
        while ($row = $result->fetch_assoc()) {
            echo '
<div class="row justify-content-center">
    <div class="col-md-6">

      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Message</h5>
        </div>
        <div class="card-body">

          <p><strong>Name:</strong> ' . htmlspecialchars($row["name"]) . '</p>
          <p><strong>Phone Number:</strong> ' . htmlspecialchars($row["tel"]) . '</p>
          <p><strong>Question:</strong> ' . htmlspecialchars($row["question"]) . '</p>

        </div>
      </div>
    </div>
</div>
';
        }
    }

    $db->close();
    ?>

</div>

<!-- ... (your existing search result display code) ... -->

<footer class="mt-5">
    <p>&copy; 2023 Bank for students. Все права защищены.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
