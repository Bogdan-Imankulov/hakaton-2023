<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4">Авторизация</h2>
        <form action="login_process.php" method="post">
            <div class="form-group">
                <label for="tel">Номер телефона:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">+</span>
                    </div>
                    <input type="tel" class="form-control" id="tel" name="tel" placeholder="7 (___) ___ __ __" required maxlength="12">
                </div>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="*********">
            </div>
            <button type="submit" class="btn btn-primary">Войти</button>
        </form>
        <div class="mt-3">
            <p>Еще нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
        </div>
    </div>

    <script>
        // Добавление "+" к номеру телефона
        document.getElementById('tel').addEventListener('input', function (e) {
            var inputValue = e.target.value;
            if (inputValue.charAt(0) !== '+') {
                e.target.value = '+' + inputValue;
            }
        });
    </script>
</body>
</html>
