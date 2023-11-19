<?php
$servername = "localhost";
$username = "p-338798_admin";
$password = "p-338798_admin";
$dbname = "p-338798_admin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tel = $conn->real_escape_string($_POST['tel']);
$password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);
$institution_name = $conn->real_escape_string($_POST['institution']);
$first_name = $conn->real_escape_string($_POST['first_name']);
$last_name = $conn->real_escape_string($_POST['last_name']);

// Проверяем, существует ли уже пользователь с таким телефоном
$check_user_sql = "SELECT * FROM users WHERE tel='$tel'";
$check_user_result = $conn->query($check_user_sql);

if ($check_user_result->num_rows > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ошибка регистрации</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/custom/payments.css">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="alert alert-danger" role="alert">
                Аккаунт с номером телефона <?php echo $tel; ?> уже зарегистрирован. Пожалуйста, используйте другой номер телефона.
            </div>
        </div>
    </body>
    </html>
    <?php
    
    // Подождать некоторое время перед перенаправлением (2 секунды в данном случае)
    header("refresh:2;url=register.php");
} else {
    // Если пользователя с таким телефоном нет, регистрируем нового пользователя
    $get_institution_id_sql = "SELECT id, name FROM institutions WHERE name='$institution_name'";
    $get_institution_id_result = $conn->query($get_institution_id_sql);

    if ($get_institution_id_result->num_rows > 0) {
        $row = $get_institution_id_result->fetch_assoc();
        $institution_id = $row['id'];
        $institution_name = $row['name'];

        // Регистрируем нового пользователя со значением balance равным 0.0
        $insert_user_sql = "INSERT INTO users (tel, password, institution, institution_name, first_name, last_name, balance) VALUES ('$tel', '$password', '$institution_id', '$institution_name', '$first_name', '$last_name', 0.0)";

        if ($conn->query($insert_user_sql) === TRUE) {
            // Генерация случайной карты и добавление информации о карте в базу данных
            $card_prefix = mt_rand(0, 1) ? '4403' : '4401';
            $card_number = $card_prefix . mt_rand(100000000000, 999999999999);
            $expiry_date = '09/26';
            $cvv_code = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $insert_card_info_sql = "INSERT INTO card_info (user_tel, card_number, expiry_date, cvv_code) VALUES ('$tel', '$card_number', '$expiry_date', '$cvv_code')";
            
            if ($conn->query($insert_card_info_sql) !== TRUE) {
                echo "Ошибка при добавлении информации о карте: " . $conn->error;
            }

            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Регистрация успешна</title>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
            </head>
            <body class="bg-light">
                <div class="container mt-5">
                    <div class="alert alert-success" role="alert">
                        Регистрация успешна. Перенаправление на страницу входа...
                    </div>
                </div>
            </body>
            </html>
            <?php
            
            // Подождать некоторое время перед перенаправлением (2 секунды в данном случае)
            header("refresh:2;url=login.php");
        } else {
            echo "Ошибка: " . $insert_user_sql . "<br>" . $conn->error;
        }
    } else {
        echo "Ошибка при получении идентификатора учреждения";
    }
}

$conn->close();
?>
