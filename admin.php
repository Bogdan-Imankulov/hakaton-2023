<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <!-- Подключение Bootstrap 5 -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.css">
    <link rel="stylesheet" href="css/bootstrap-utilities.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/custom/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<!-- Использование Bootstrap для навигационного меню -->
<nav class="container container-fluid" id="button-container">
    <button id="return-back-btn"  onclick="window.history.back()">
        <img src="img/backarrow.svg" alt="назад" width="40">
    </button>
    <div id="head">
        <h1>
            Админ-панель
        </h1>
    </div>
    <button id="head-gap"  onclick="window.location.href = 'main.php'">
        <img id="logout" src="img/logout.png" alt="выйти" width="25">
    </button>
</nav>

<section id="users-review">
    <h2>Сводка пользователей</h2>
    <div class="user-wrap">
        <h3 class="user-name">Андрей Дрей Дрей</h3>
        <div class="user-data">
            <div class="credit">
                <h4 class="credit-header">Список кредитов:</h4>
                <ul class="credits-ul">
                    <li>
                        <div class="credit-wrap">
                            <h5 class="credit-name">Кредит за обучение</h5>
                            <p class="credit-sum">Сумма: 2.000.000</p>
                            <p class="credit-date">Месяц истечения: 06/24</p>
                            <p class="credit-percent">Процентная ставка: 12%</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="other-data">
                <div class="data-div balance">
                    <h4><span class="data-div-header">На счету</span> <hr/></h4>
                    <p>12341 &#8376;</p>
                </div>
                <div class="data-div institute">
                    <h4><span class="data-div-header">Институт</span><hr/></h4>
                    <p>Технический Инновационный Государственный Университет</p>
                </div>
                <div class="data-div salary">
                    <h4><span class="data-div-header">Стипендия</span><hr/></h4>
                    <p>234123</p>
                </div>
                <div class="data-div bank-card">
                    <h4><span class="data-div-header">Номер карты</span><hr/></h4>
                    <p>4412 3323 2234 1134</p>
                </div>
            </div>
        </div>
    </div>
    <div class="user-wrap">
        <h3 class="user-name">Андрей Дрей Дрей</h3>
        <div class="user-data">
            <div class="credit">
                <h4 class="credit-header">Список кредитов:</h4>
                <ul class="credits-ul">
                    <li>
                        <div class="credit-wrap">
                            <h5 class="credit-name">Кредит за обучение</h5>
                            <p class="credit-sum">Сумма: 2.000.000</p>
                            <p class="credit-date">Месяц истечения: 06/24</p>
                            <p class="credit-percent">Процентная ставка: 12%</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="other-data">
                <div class="data-div balance">
                    <h4><span class="data-div-header">На счету</span> <hr/></h4>
                    <p>12341 &#8376;</p>
                </div>
                <div class="data-div institute">
                    <h4><span class="data-div-header">Институт</span><hr/></h4>
                    <p>Технический Инновационный Государственный Университет</p>
                </div>
                <div class="data-div salary">
                    <h4><span class="data-div-header">Стипендия</span><hr/></h4>
                    <p>234123</p>
                </div>
                <div class="data-div bank-card">
                    <h4><span class="data-div-header">Номер карты</span><hr/></h4>
                    <p>4412 3323 2234 1134</p>
                </div>
            </div>
        </div>
    </div>
    <div class="user-wrap">
        <h3 class="user-name">Андрей Дрей Дрей</h3>
        <div class="user-data">
            <div class="credit">
                <h4 class="credit-header">Список кредитов:</h4>
                <ul class="credits-ul">
                    <li>
                        <div class="credit-wrap">
                            <h5 class="credit-name">Кредит за обучение</h5>
                            <p class="credit-sum">Сумма: 2.000.000</p>
                            <p class="credit-date">Месяц истечения: 06/24</p>
                            <p class="credit-percent">Процентная ставка: 12%</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="other-data">
                <div class="data-div balance">
                    <h4><span class="data-div-header">На счету</span> <hr/></h4>
                    <p>12341 &#8376;</p>
                </div>
                <div class="data-div institute">
                    <h4><span class="data-div-header">Институт</span><hr/></h4>
                    <p>Технический Инновационный Государственный Университет</p>
                </div>
                <div class="data-div salary">
                    <h4><span class="data-div-header">Стипендия</span><hr/></h4>
                    <p>234123</p>
                </div>
                <div class="data-div bank-card">
                    <h4><span class="data-div-header">Номер карты</span><hr/></h4>
                    <p>4412 3323 2234 1134</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bottom">
    <p>&copy; 2023 bcckz.ru Все права защищены</p>
</footer>

<!-- Подключение Bootstrap 5 JavaScript (необходимо для работы некоторых компонентов) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


