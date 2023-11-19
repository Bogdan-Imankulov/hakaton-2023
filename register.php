<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>

<nav class="container container-fluid" id="button-container">
    <button id="return-back-btn" onclick="window.history.back()">
        <img src="img/backarrow.svg" alt="назад" width="40">
    </button>
    <div id="head">
        <h1>
            Зарегистрироваться
        </h1>
    </div>
    <div id="head-gap">
    </div>
</nav>
    <div class="container mt-5">
        <h2 class="mb-4">Регистрация</h2>
        <form action="register_process.php" method="post">
            <div class="form-group">
                <label for="tel">Номер телефона:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">+</span>
                    </div>
                    <input type="tel" class="form-control" id="tel" name="tel" required maxlength="12">
                </div>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="institution">Выберите учреждение:</label>
                <select class="form-control" id="institution" name="institution" required>
                    <?php
                    $servername = "localhost";
                    $username = "p-338798_admin";
                    $password = "p-338798_admin";
                    $dbname = "p-338798_admin";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $institution_sql = "SELECT id, name FROM institutions";
                    $institution_result = $conn->query($institution_sql);

                    if ($institution_result->num_rows > 0) {
                        while ($row = $institution_result->fetch_assoc()) {
                            echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                        }
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="first_name">Имя:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Фамилия:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
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
