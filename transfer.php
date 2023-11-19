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
$first_name = 'fn';
$last_name = 'ln';
$user_balance = 0;
$conn = new mysqli($servername, $username, $password, $dbname);
//// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//// Получаем баланс из базы данных для текущего пользователя
if (isset($_SESSION['tel'])) {
    $tel = $_SESSION['tel'];

    $sql = "SELECT balance, first_name, last_name FROM users WHERE tel = '$tel'";
    $result = $conn->query($sql);

    if ($result === false) {
        // Выводим сообщение об ошибке SQL
        echo "Error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            // Выводим данные каждой строки
            while ($row = $result->fetch_assoc()) {
                $user_balance = $row["balance"];
                $first_name = $row["first_name"];
                $last_name = $row["last_name"];
            }
        } else {
            echo "0 results";
        }
    }
} else {
    echo "User telephone not found in session.";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Перевести</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.css">
    <link rel="stylesheet" href="css/bootstrap-utilities.css">
    <link rel="stylesheet" href="css/custom/transfer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/nav.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>
<body>
<nav class="container container-fluid" id="button-container">
    <button id="return-back-btn" onclick="window.history.back()">
        <img src="img/backarrow.svg" alt="назад" width="40">
    </button>
    <div id="head">
        <h1>
            Перевести
        </h1>
    </div>
    <div id="head-gap">
    </div>
</nav>
<div class="container " id="form-wrapper">
    <div class="row">
        <div class="col-md-6 offset-md-1">
            <!-- Форма пополнения -->
            <form id="withdraw-form" action="transfer-process.php" method="post">
                <div id="phone-data" class="payment-data">
                    <div class="mb-3">
                        <script>
                            function formatPhoneNumber(input) {
                                // Удаляем все символы, кроме цифр
                                var phoneNumber = input.value.replace(/\D/g, '');
                                // Добавляем символ + и форматируем номер
                                if (phoneNumber.length > 0) {
                                    phoneNumber = '+' + phoneNumber.slice(0, 11);
                                }
                                // Ограничиваем ввод 12 символами
                                if (phoneNumber.length > 12) {
                                    phoneNumber = phoneNumber.slice(0, 12);
                                }
                                // Устанавливаем отформатированный номер обратно в поле ввода
                                input.value = phoneNumber;
                            }
                        </script>
                        <div class="mb-3">
                            <label class="form-label" for="phone-num">Номер телефона</label>
                            <div class="mb-3">
                                <input type="tel" class="form-control" id="phone-num" name="phone-num"
                                       placeholder="+7 (___) ___ ____"
                                       maxlength="12" oninput="formatPhoneNumber(this)" required>
                            </div>
                            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="phoneToast">
                                <div class="toast-header">
                                    <strong class="me-auto text-danger">Ошибка в номере телефона</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast"
                                            aria-label="Close"></button>
                                </div>
                                <div class="toast-body" id="phoneToastBody">
                                    Введите корректный номер телефона
                                </div>
                            </div>
                            <div class="toast" role="contentinfo" aria-live="assertive" aria-atomic="true"
                                 id="receiverToast">
                                <div class="toast-header">
                                    <strong class="me-auto text-success">Получатель:</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast"
                                            aria-label="Close"></button>
                                </div>
                                <div class="toast-body" id="receiverToastBody">
                                    <!-- Имя и фамилия получателя будут заменены данными из запроса к серверу -->
                                    <p style="margin-bottom: 0"><span id="receiver-first-name"></span> <span
                                                id="receiver-last-name"></span></p>
                                </div>
                            </div>
                        </div>
                        <script>
                            function formatPhoneNumber(input) {
                                // ... (оставьте этот блок без изменений) ...
                            }

                            function getReceiverInfo(receiver_tel) {
                                // Проверка, что номер телефона не пустой
                                if (receiver_tel.trim() !== "") {
                                    // Формируем URL запроса к PHP-скрипту, передавая номер телефона
                                    var url = "receiver_info.php?tel=" + encodeURIComponent(receiver_tel);

                                    // Отправляем GET-запрос
                                    fetch(url)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.error) {
                                                // Выводим ошибку, если есть
                                                console.error(data.error);
                                            } else if (data.first_name && data.last_name) {
                                                // Заполняем данные в тосте
                                                document.getElementById('receiverToastBody').innerText = data.first_name + ' ' + data.last_name;
                                                // Показываем тост
                                                var receiverToast = new bootstrap.Toast(document.getElementById('receiverToast'));
                                                receiverToast.show();
                                            } else {
                                                // Если данные не найдены, выводим сообщение
                                                console.error('Получатель не найден');
                                            }
                                        })
                                        .catch(error => console.error(error));
                                }
                            }

                            // ... (оставьте этот блок без изменений) ...
                        </script>
                    </div>
                </div>

        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Сумма вывода</label>
           
<input type="number" class="form-control" name="amount" id="amount" placeholder="Введите сумму" required>





            <div id="available-wrap">
                <p id="balance-msg">На счету:</p>
                <p id="balance"><span id="monet_amount"><?php echo $user_balance; ?><span id="balance-decimals"></span></span>
                    <span id="currency_span">₸</span></p>
            </div>
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="amountToast">
                <div class="toast-header">
                    <strong class="me-auto text-danger">Слишком малая сумма</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body" id="amountToastBody">
                    Минимальная сумма перевода: 100тг
                </div>
            </div>
        </div>
        <button id="form-btn" type="button" class="btn btn-success" onclick="validateAndShowToasts()">
            Перевести
        </button>
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="successToast">
            <div class="toast-header">
                <strong class="me-auto text-success">Перевод в обработке!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="successToastBody">
                <p>Перевод в обработке!<br>Время: <span id="success-date"></span><br>Сумма:
                    <span id="success-amount"></span><br>Отправитель: <span
                            id="success-sender"><?php echo $first_name . ' ' . $last_name ?></span> <br>Получатель:
                    <span id="success-receiver"> </span>
                </p>
            </div>
        </div>
        </form>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>




<script>
document.getElementById('amount').addEventListener('input', function() {
    var min = 100;
    if (this.validity.rangeUnderflow) {
        this.setCustomValidity('Минимальное значение должно быть ' + min);
    } else {
        this.setCustomValidity('');
    }
});
</script>



<script>
    let isPhone = true;
    const cardDiv = document.getElementById('card-data');
    const phoneDiv = document.getElementById('phone-data');

    const phoneBtn = document.getElementById('phone-btn');
    const cardBtn = document.getElementById('card-btn');

    function changeReceiver() {
        if (isPhone) {
            cardDiv.style.display = 'none';
            phoneDiv.style.display = 'block';
            phoneBtn.classList.add('checked');
            cardBtn.classList.remove('checked');
        } else {
            cardDiv.style.display = 'block';
            phoneDiv.style.display = 'none';
            phoneBtn.classList.remove('checked');
            cardBtn.classList.add('checked');
        }
    }
</script>

<script>
    let amountToast = new bootstrap.Toast(document.getElementById('amountToast'));
    let phoneToast = new bootstrap.Toast(document.getElementById('phoneToast'));

    let phone = document.getElementById('phone-data');

    let phoneNumber = document.getElementById('phone-num').value;
    let amount = document.getElementById('amount').value;
    let form = document.getElementById('withdraw-form');

    let isAmount = parseInt(amount) < 100;
    let isPhoneNumber = !(/\+?\d[(\s-]?\d{3}[)\s-]?\d{3}[\s-]?\d{2}[\s-]?\d{2}/.test(phoneNumber));

    let receiverFirstName = '';
    let receiverLastName = '';

    document.getElementById('phone-num').addEventListener('input', function (event) {
        let receiverToast = new bootstrap.Toast(document.getElementById('receiverToast'));
        if ((/\+?\d[(\s-]?\d{3}[)\s-]?\d{3}[\s-]?\d{2}[\s-]?\d{2}$/.test(event.target.value))) {
            // Устанавливаем таймер на 0.5 секунд и вызываем функцию
            setTimeout(function () {
                callPhpFunction();
                receiverToast.show();
                phoneToast.hide();
            }, 500);
        } else {
            receiverToast.hide();
        }
    })


    function validateAndShowToasts() {
        let phoneNumber = document.getElementById('phone-num').value;
        let isPhoneNumber = !(/\+?\d[(\s-]?\d{3}[)\s-]?\d{3}[\s-]?\d{2}[\s-]?\d{2}/.test(phoneNumber));
        if (isAmount) {
            showToast('amount')
        }

        // Проверка номера телефона - универсальный regex
        if (isPhoneNumber) {
            showToast('phone');
        }
        if (!isAmount && !isPhoneNumber) {

            console.log('okokok')
            // Получение данных для отображения в Toast
            let successToast = new bootstrap.Toast(document.getElementById('successToast'));

            // Создание строки с информацией о переводе
            // const toastMessage = `Перевод успешен!<br>Время: ${currentTime}<br>Сумма: ${transferAmount}<br>Отправитель: ${sender}<br>Получатель: ${recipient}`;
            const successDate = document.getElementById('success-date');
            const successAmount = document.getElementById('success-amount');
            const successReceiver = document.getElementById('success-receiver')
            const amount = document.getElementById('amount').value;
            const receiver = document.getElementById('receiver-first-name').textContent + ' ' + receiverLastName;
            const currentTime = new Date().toLocaleTimeString();
            successReceiver.textContent = receiver;
            successDate.textContent = currentTime;
            successAmount.textContent = amount + '';
            successToast.show();
            // Отображение Toast
            setTimeout(function () {
                form.submit();

            }, 4000);
        }
    }

    function showToast(type) {
        if (type === 'amount') {
            amountToast.show();
        }
        if (type === 'phone') {
            phoneToast.show();
        }
        // Другие типы toast-ов, если необходимо, могут быть добавлены аналогичным образом
    }
</script>
<script>
    // Функция для вызова PHP-функции
    function callPhpFunction() {
        let receiver_tel = document.getElementById('phone-num').value;
        console.log(receiver_tel)// Задайте значение параметра
        $.ajax({
                type: "POST",
                url: "getReceiverName.php", // Укажите путь к вашему PHP-скрипту
                data: {receiver_tel: receiver_tel},
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response)[0];
                    try {
                        receiverFirstName = data.first_name;
                        receiverLastName = data.last_name;
                        document.getElementById('receiver-first-name').textContent = data.first_name;
                        document.getElementById('receiver-last-name').textContent = data.last_name;
                    } catch (err) {
                        document.getElementById('receiver-first-name').textContent = '';
                        document.getElementById('receiver-last-name').textContent = '';
                        document.getElementById('receiver-first-name').textContent = 'Пользователя с таким номером не существует'

                    }
                }
            }
        );
    }
</script>



<script>
document.getElementById('amount').addEventListener('input', function() {
    var min = 100;
    if (parseInt(this.value) < min) {
        this.setCustomValidity('Минимальное значение должно быть ' + min);
    } else {
        this.setCustomValidity('');
    }
});
</script>


</body>
</html>
