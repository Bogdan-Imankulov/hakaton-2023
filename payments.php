<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, была ли отправлена форма методом POST

    // Устанавливаем параметры для подключения к базе данных
    $db_host = 'localhost';
    $db_user = 'p-338798_admin';
    $db_password = 'p-338798_admin';
    $db_name = 'p-338798_admin';

    // Создаем соединение с базой данных
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Проверяем соединение на ошибки
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Получаем данные из формы;
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $patronymic = $conn->real_escape_string($_POST['patronymic']);
    $iin = $conn->real_escape_string($_POST['iin']);
    $loanAmount = $conn->real_escape_string($_POST['loanAmount']);

    // SQL-запрос для вставки данных в базу данных
    $sql = "INSERT INTO credits (firstName, lastName, patronymic, iin, loanAmount) 
            VALUES ('$firstName', '$lastName', '$patronymic', '$iin', '$loanAmount')";

    // Выполняем запрос
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
$user_balance = 0;
$sql = "SELECT balance FROM users WHERE first_name = '$firstName' AND last_name = '$lastName'";
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

    // Update sender's balance (subtract transfer amount)
    $new_user_balance = $user_balance + $loanAmount;
    $userSql = "UPDATE users SET balance = '$new_user_balance' WHERE first_name = '$firstName' AND last_name = '$lastName'";
    $conn->query($userSql);

    // Закрываем соединение с базой данных
    $conn->close();
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Платежи</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.css">
    <link rel="stylesheet" href="css/bootstrap-utilities.css">
    <link rel="stylesheet" href="css/custom/payments.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
<nav class="container container-fluid" id="button-container">
    <button id="return-back-btn" onclick="window.history.back()">
        <img src="img/backarrow.svg" alt="назад" width="40">
    </button>
    <div id="head">
        <h1>
            Платежи
        </h1>
    </div>
    <div id="head-gap">
    </div>
</nav>
<div class="container mt-5">
    <h2>Оформление кредита</h2>
    <form action="" method="post" id="credit-form">
        <div class="form-group">
            <label for="firstName">Имя:</label>
            <input type="text" class="form-control" id="firstName" name="firstName" required>
        </div>
        <div class="form-group">
            <label for="lastName">Фамилия:</label>
            <input type="text" class="form-control" id="lastName" name="lastName" required>
        </div>


        <div class="form-group">
            <label for="patronymic">Отчество:</label>
            <input type="text" class="form-control" id="patronymic" name="patronymic"
                   oninput="limitIINLength(this, 12)">
        </div>

        <div class="form-group">
            <label for="iin">ИИН:</label>
            <input type="text" class="form-control" id="iin" name="iin" maxlength="12" required>
        </div>

        <!-- Ваш существующий код ... -->

        <script>
            function limitIINLength(input, maxLength) {
                let inputValue = input.value;
                if (inputValue.length > maxLength) {
                    input.value = inputValue.slice(0, maxLength);
                }
            }
        </script>

        <div class="form-group">
            <label for="credit-amount">Сумма получения:</label>
            <input type="text" class="form-control" id="credit-amount" name="loanAmount" maxlength="6" required>
        </div>

        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="amountToast">
            <div class="toast-header">
                <strong class="me-auto text-danger">Слишком малая сумма</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="amountToastBody">
                Минимальная сумма кредита: 10000тг
            </div>
        </div>
        <div class="form-group">
            <p class="fixed-credit-text">Срок 12 месяцев:</p>
            <p class="fixed-credit-text">Ставка 20%:</p>
        </div>
        <button type="button" onclick="submitCredit()" class="btn btn-primary" id="credit-submit">Оформить кредит
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let loanAmount = document.getElementById('credit-amount');
    let creditBtn = document.getElementById('credit-submit');
    document.getElementById('credit-amount').addEventListener('input', function () {
        let loanValue = loanAmount.value;
        console.log(loanValue)
        if (loanValue >= 10000) {
            creditBtn.textContent = 'Оформить кредит под: ' + Math.round(loanValue * 1.2 / 12) + ' тг в месяц'
            console.log(creditBtn.textContent)
        } else {
            creditBtn.textContent = 'Оформить кредит'
            console.log(creditBtn.textContent)

        }
    });

    function submitCredit() {
        let loanValue = loanAmount.value;
        if (loanValue > 10000) {
            document.getElementById('credit-form').submit();
        } else {
            let amountToast = new bootstrap.Toast(document.getElementById('amountToast'));
            amountToast.show();
        }
    }
</script>
</body>
</html>