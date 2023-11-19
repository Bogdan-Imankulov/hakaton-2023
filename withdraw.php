<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вывести</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.css">
    <link rel="stylesheet" href="css/bootstrap-utilities.css">
    <link rel="stylesheet" href="css/custom/withdraw.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
<nav class="container container-fluid" id="button-container">
    <button id="return-back-btn" onclick="window.history.back()">
        <img src="img/backarrow.svg" alt="назад" width="40">
    </button>
    <div id="head">
        <h1 class="text-center">Вывести</h1>
    </div>
    <div id="head-gap">
    </div>
</nav>
<div class="container mt-5" id="form-wrapper">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <!-- Форма пополнения -->
            <form id="withdraw-form">
                <div class="mb-3">
                    <label for="amount" class="form-label">Сумма вывода</label>
                    <input type="text" class="form-control" id="amount" placeholder="Введите сумму" required min="">
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

                <div class="mb-3" id="select-wrap">
                    <label class="form-label" for="withdraw-address">Вывести на: </label>
                    <select class="form-select" id="withdraw-address">
                        <option value="phone">Номер телефона</option>
                        <option value="card">Банковскую карту</option>
                        <option value="paypal">PayPal</option>
                        <option value="btc">Bitcoin</option>
                    </select>
                </div>
                <div id="card-data" class="payment-data">

                    <div class="mb-3">
                        <label class="form-label" for="card-num">Номер карты</label>
                        <input type="number" class="form-control" id="card-num" placeholder="____ ____ ____ ____">
                        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="creditCardToast">
                            <div class="toast-header">
                                <strong class="me-auto text-danger">Ошибка в номере карты</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body" id="creditCardToastBody">
                                Введите корректный номер карты <br />(16 цифр)
                            </div>
                        </div>
                    </div>

                </div>


                <div id="phone-data" class="payment-data">

                    <div class="mb-3">
                        <label class="form-label" for="phone-num">Номер телефона</label>
                        <input type="tel" class="form-control" id="phone-num" placeholder="+7 (___) ___ ____" required >
                        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="phoneToast">
                            <div class="toast-header">
                                <strong class="me-auto text-danger">Ошибка в номере телефона</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body" id="phoneToastBody">
                                Введите корректный номер телефона
                            </div>
                        </div>
                    </div>

                </div>
                <button id="form-btn" type="button" class="btn btn-success" onclick="validateAndShowToasts()">Пополнить</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
    document.getElementById('payment-method').addEventListener('change', function() {
        let card = document.getElementById('card-data');
        let phone = document.getElementById('phone-data');

        // Если выбрана опция "Другая банковская карта", показываем div с id card-data, иначе скрываем его
        card.style.display = this.value === 'card' ? 'block' : 'none';
        if (this.value === 'card') {
            card.style.display = 'block';
            card.setAttribute('required', '');
            phone.style.display = 'none';
            phone.removeAttribute('required');
        } else {
            phone.style.display = 'block';
            phone.setAttribute('required', '')
            card.style.display = 'none';
            card.removeAttribute('required');
        }
        phone.style.display = this.value === 'phone' ? 'block' : 'none';
    });

    function validateAndShowToasts() {
        let creditCardNumber = document.getElementById('card-num').value;
        let phoneNumber = document.getElementById('phone-num').value;
        let amount = document.getElementById('amount').value;

        let form = document.getElementById('withdraw-form')

        let isAmount = parseInt(amount) < 100;
        let isCreditCardNumber = !(/^\d{16}$/.test(creditCardNumber));
        let isPhoneNumber = !(/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/.test(phoneNumber));

        if (!isAmount && !isPhoneNumber && ! isCreditCardNumber) {
            form.submit();
        }

        if (isAmount) {
            showToast('amount')
        }
        // Проверка номера банковской карты - 16 символов
        if (isCreditCardNumber) {
            showToast('creditCard');
        }

        // Проверка номера телефона - универсальный regex
        if (isPhoneNumber) {
            showToast('phone');
        }


    }

    function showToast(type) {
        if (type === 'amount') {
            var amountToast = new bootstrap.Toast(document.getElementById('amountToast'));
            amountToast.show();
        }
        if (type === 'creditCard') {
            var creditCardToast = new bootstrap.Toast(document.getElementById('creditCardToast'));
            creditCardToast.show();
        } else if (type === 'phone') {
            var phoneToast = new bootstrap.Toast(document.getElementById('phoneToast'));
            phoneToast.show();
        }
        // Другие типы toast-ов, если необходимо, могут быть добавлены аналогичным образом
    }
</script>

</body>
</html>
