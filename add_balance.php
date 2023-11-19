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

    $sql = "SELECT balance FROM users WHERE tel = ?";

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
                $user_balance = $row["balance"];
            }
        } else {
            echo "0 results";
        }
    }

    $stmt->close(); // Закрываем подготовленный запрос
} else {
    echo "User telephone not found in session.";
}

// Обработка формы пополнения баланса
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["amount"])) {
        $amount = $_POST["amount"];

        // Пополнение баланса
        $new_balance = $user_balance + $amount;

        $update_sql = "UPDATE users SET balance = $new_balance WHERE tel = '$tel'";

        // Используем подготовленный запрос для предотвращения SQL-инъекций
        $update_stmt = $conn->query($update_sql);

        if ($update_stmt === false) {
            // Выводим сообщение об ошибке SQL
            echo "Error: " . $conn->error;
        } else {
            // Баланс успешно пополнен!

            // Выводим модальное окно Bootstrap
            echo '  <!-- Подключение Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
  <div class="alert alert-success" role="alert">
    Баланс успешно пополнен
  </div>
</div>



<!-- Подключение Bootstrap JS (необходимо для работы определенных компонентов) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>';

            // Добавляем скрипт для отображения модального окна и перенаправления через 2 секунды
            echo '<script>
                    $("#successModal").modal("show");
                    setTimeout(function(){
                        window.location.href = "main.php";
                    }, 2000);
                  </script>';
        }

        $update_stmt->close(); // Закрываем подготовленный запрос
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Пополнить</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.css">
    <link rel="stylesheet" href="css/bootstrap-utilities.css">
    <link rel="stylesheet" href="css/custom/transfer.css">
    <link rel="stylesheet" href="css/nav.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>

<nav class="container container-fluid" id="button-container">
    <button id="return-back-btn" onclick="window.history.back()">
        <img src="img/backarrow.svg" alt="назад" width="40">
    </button>
    <div id="head">
        <h1>
            Пополнить
        </h1>
    </div>
    <div id="head-gap">
    </div>
</nav>
<div class="container mt-5" id="form-wrapper">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <!-- Форма пополнения -->
            <form id="main-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <!-- ... -->
                <div class="mb-3">
                    <label for="amount" class="form-label">Сумма пополнения</label>
                    <input type="text" name="amount" class="form-control" id="amount" placeholder="Введите сумму" maxlength="7" required>
                </div>

                <div id="card-data" class="" style="display: block">

                    <div class="mb-3">
                        <label class="form-label">Номер карты</label>
                        <input type="text" class="form-control" id="card-num" placeholder="____ ____ ____ ____" maxlength="16" required>
                    </div>

                    <div class="mb-3" id="date-cvv-wrap">
                        <div id="date-wrap">
                            <label class="form-label">Дата истечения</label>
                            <input type="text" class="form-control" id="card-date" placeholder="MM / YY" maxlength="5" required>
                        </div>

                        <div id="cvv-wrap">
                            <label class="form-label">CVV</label>
                            <input type="password" class="form-control" id="card-cvv" placeholder="***" maxlength="3" required>
                        </div>
                    </div></div>

                <button type="button" class="btn btn-success" onclick="submitForm()" id="submit-btn">Пополнить</button>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('payment-method').addEventListener('change', function() {
        var cardDataDiv = document.getElementById('card-data');

        // Если выбрана опция "Другая банковская карта", показываем div с id card-data, иначе скрываем его
        cardDataDiv.style.display = this.value === 'other_card' ? 'block' : 'none';
    });

    function submitForm() {
        let form = document.getElementById("main-form");
        setTimeout(function() {
            form.submit();
        }, 1000);
    }
</script>
<!-- Подключение Bootstrap 5 JS и Popper.js (необходимо для некоторых компонентов) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>

    // Функция для форматирования даты (добавление / между месяцем и годом)
    function formatCardDate(value) {
        return value.replace(/\s/g, '').replace(/(\d{2})(\d{2})/g, '$1 / $2').trim();
    }

    document.getElementById('card-num').addEventListener('input', function (e) {
        this.value = formatCardNumber(this.value);
    });

    document.getElementById('card-date').addEventListener('input', function (e) {
        this.value = formatCardDate(this.value);
    });
</script>
</body>
</html>