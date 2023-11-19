<?php
session_start();

//// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || time() > $_SESSION['expiry_time']) {
    // Пользователь не авторизован или сессия истекла, перенаправляем на страницу авторизации
    header("Location: login.php");
    exit();
}

// Update session timeout
$_SESSION["timeout"] = time() + 1200;

// Update session timeout
//$_SESSION["timeout"] = time() + 1200;

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
$tel = $_SESSION["tel"];
$sql = "SELECT * FROM users WHERE tel = '$tel'";
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch data and store it in the $userInfo array
    while ($row = $result->fetch_assoc()) {
        $userInfo['institution_name'] = $row['institution_name'];
        $userInfo['tel'] = $row['tel'];
        $userInfo['first_name'] = $row['first_name'];
        $userInfo['last_name'] = $row['last_name'];
        $userInfo['balance'] = $row['balance'];
    }
} else {
    echo "Error in the database query";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #profile {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #28ae86; /* Bootstrap green color */
            color: #fff; /* White text on green background */
        }
        h2 {
            background-color: white;
            padding: 10px;
            border-radius: 2px;
            color: #27ae60;
            font-size: 1.5rem;
        }
        #profile-info {
            margin-left: 10px;
        }
        #row-actions {
            border-top: 2px solid #27ae60;
            /*border-bottom: 2px solid #27ae60;*/
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr;
            height: 7vh;
            padding-top: 1vh;
            /*justify-self: end;*/
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding-bottom: 2vh;
        }
        .action {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;;
            justify-self: center;
            align-self: center;
        }
        .action img {
            /*margin-top: 10px;*/
            justify-self: center;
            align-self: center;
        }
        .action p {
            height: fit-content;
        }
    </style>
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>

<nav class="container container-fluid" id="button-container">
    <button id="return-back-btn"  onclick="window.history.back()">
        <img src="img/backarrow.svg" alt="назад" width="40">
    </button>
    <div id="head">
        <h1>
            Мой Банк
        </h1>
    </div>
    <div id="head-gap">
    </div>
</nav>
<div id="profile">
    <h2>Профиль пользователя</h2>
    <div id="profile-info">
        <p><strong>Институт:</strong> <span id="institution_name"><?php echo $userInfo['institution_name']; ?></span></p>
        <p><strong>Телефон:</strong> <span id="tel"><?php echo $userInfo['tel']; ?></span></p>
        <p><strong>Имя:</strong> <span id="first_name"><?php echo $userInfo['first_name']; ?></span></p>
        <p><strong>Фамилия:</strong> <span id="last_name"><?php echo $userInfo['last_name']; ?></span></p>
        <p><strong>Баланс:</strong> <span id="balance"><?php echo $userInfo['balance']; ?></span> тг.</p>
    </div>
</div>
<div id="row-actions">
    <div class="action" onclick="window.location.href = 'main.php'">
        <img src="img/home.png" width="30" alt=""/>
        <p>Главная</p>
    </div>
    <div class="action" onclick="window.location.href='admin/adm-index.php'">
        <img src="img/sms.png" width="30" alt=""/>
        <p>Помощь</p>
    </div>
</div>
<!-- Include Bootstrap JS and Popper.js (optional, for Bootstrap's JavaScript features) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

