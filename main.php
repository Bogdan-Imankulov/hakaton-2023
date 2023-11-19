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



// Карта

if (isset($_SESSION['tel'])) {
    $tel = $_SESSION['tel'];

    // Измененный SQL-запрос для выборки данных карты
    $sql = "SELECT card_number, expiry_date, cvv_code FROM card_info WHERE user_tel = ?";
    
    // Используем подготовленный запрос для предотвращения SQL-инъекций
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tel);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result === false) {
        // Выводим сообщение об ошибке SQL
        echo "Error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            // Выводим данные каждой строки
            while ($row = $result->fetch_assoc()) {
                $card_number = $row["card_number"];
                $expiry_date = $row["expiry_date"];
                $cvv_code = $row["cvv_code"];
            }
        } else {
            echo "";
        }
    }
    
    $stmt->close(); // Закрываем подготовленный запрос
} else {
    echo "User telephone not found in session.";
}

$conn->close();
?>

<!DOCTYPE html>
<!-- ... (оставшаяся часть вашего HTML-кода) -->

<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ваша страница</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.css">
    <link rel="stylesheet" href="css/bootstrap-utilities.css">
    <link rel="stylesheet" href="css/custom/main.css">
    <link rel="stylesheet" href="css/nav.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
<div id="darker"></div>

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
<section id="my_bank">
    <div id="bank-info">
        <p id="balance-msg">На счету:</p>
        <p id="balance"><span id="monet_amount"><?php echo $user_balance;  ?><span
                        id="balance-decimals"></span></span> <span id="currency_span">₸</span></p>

        <button id="requisites-button" onclick="showRequsities()">
            Реквизиты
        </button>
    </div>
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="requsities">
        <div class="toast-header" id="toast-head">
            <strong class="me-auto text-success" id="toast-head-text">ДАННЫЕ КАРТЫ</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-body-wrap">
            <p class="toast-text">Номер карты: <span><?php echo $card_number; ?></span></p>
            <p class="toast-text">Дата истечения: <span><?php echo $expiry_date; ?></span></p>
            <div id="cvv-wrap">
                <p class="toast-text" id="cvv-p">CVV: <span id="cvv" class="hidden"><?php echo $cvv_code; ?></span></p>
                <img onclick="showCVV()" id="eye" src="img/hide_password_eye_icon.svg" width="15" alt=""/>
            </div>
        </div>
    </div>
</section>
<section id="actions">
    <div id="main-actions">
        <div class="action " onclick="window.location.href = 'transfer.php'">
            <img src="img/money-transfer.png" width="30" alt=""/>
            <p id="action-text">Перевести</p>
        </div>
        <div class="action " onclick="window.location.href = 'add_balance.php'">
            <img src="img/add-balance.png" width="30" alt=""/>
            <p>Пополнить</p>
        </div>
        <div class="action " onclick="window.location.href = 'payments.php'">
            <img src="img/payments.png" width="30" alt=""/>
            <p>Кредиты</p>
        </div>
        <div class="action " onclick="window.location.href = 'prof.php'">
            <img src="img/user.png" width="30" alt=""/>
            <p>Профиль</p>
        </div>
    </div>
</section>

<div id="row-actions">
    <div class="action">
        <img src="img/home.png" width="30" alt=""/>
        <p>Главная</p>
    </div>
    <div class="action">
        <img src="img/sms.png" width="30" alt=""/>
        <p>Помощь</p>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.1/dist/umd/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
    let hidden = true;
    function showCVV() {
        let eye = document.getElementById('eye');
        let cvv = document.getElementById('cvv');
        if (hidden) {
            hidden = false;
            eye.setAttribute('src', 'img/show_password_eye.svg');
            cvv.classList.remove('hidden');
            cvv.classList.add('shown');
        } else {
            hidden = true;
            eye.setAttribute('src', 'img/hide_password_eye_icon.svg');
            cvv.classList.remove('shown');
            cvv.classList.add('hidden');
        }
    }
</script>
<script>
    let startY;

    document.addEventListener('touchstart', (e) => {
        // Записываем начальную позицию при касании
        startY = e.touches[0].clientY;
    });

    document.addEventListener('touchmove', (e) => {
        // Вычисляем разницу в вертикальном смещении
        let deltaY = e.touches[0].clientY - startY;

        // Если свайп вниз
        if (deltaY > 0) {
            // Предотвращаем стандартное поведение прокрутки
            e.preventDefault();
        }
    });

    document.addEventListener('touchend', (e) => {
        let deltaY = e.changedTouches[0].clientY - startY;
        // Если свайп завершен, обновляем страницу
        if (deltaY > 50) {
            document.getElementById("darker").style.zIndex = "999"
            document.getElementById("darker").classList.add("darken")
            setTimeout(() => {
                // Обновляем страницу
                location.reload();
            }, 1000);
        }
    });
</script>
<script>
    function showRequsities() {
        let requsities = document.getElementById('requsities');
        let toastHeadText = document.getElementById('toast-head-text')
        let toastBodyWrap = document.getElementById('toast-body-wrap')

        let bankHeight = parseInt(getComputedStyle(document.getElementById('my_bank')).height);
        let actionFontSize = parseInt(getComputedStyle(document.getElementById('action-text')).fontSize);

        requsities.style.width = 100 + 'vw'
        requsities.style.height = bankHeight + 'px';
        toastHeadText.style.fontSize = (actionFontSize * 1.3) + 'px'
        toastBodyWrap.style.fontSize = (actionFontSize * 1.1) + 'px'

        let requsitiesToast = new bootstrap.Toast(requsities, {
            autohide: false
        });
        requsitiesToast.show();

    }
</script>
</body>

</html>
