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
$transfer_amount = 0;

//// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//// Получаем баланс из базы данных для отправителя
if (isset($_SESSION['tel'])) {
    $tel = $_SESSION['tel'];
    $sender_balance = 0;
    $sql = "SELECT balance FROM users WHERE tel = '$tel'";
    $result = $conn->query($sql);

    if ($result === false) {
        // Выводим сообщение об ошибке SQL
        echo "Error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            // Выводим данные каждой строки
            while ($row = $result->fetch_assoc()) {
                $sender_balance = $row["balance"];
            }
        } else {
            echo "0 results";
        }
    }

    // Get the transfer amount from the form
    $transfer_amount = $_POST['amount'];


    // Check if the sender has sufficient balance
    if ($sender_balance >= $transfer_amount && $transfer_amount >= 100) {
        // Update sender's balance (subtract transfer amount)
        $new_sender_balance = $sender_balance - $transfer_amount;
        $update_sender_sql = "UPDATE users SET balance = '{$new_sender_balance}' WHERE tel = '{$tel}'";
        $conn->query($update_sender_sql);


        // Get receiver's information
        $receiver_tel = $_POST['phone-num'];
        $get_receiver_sql = "SELECT balance FROM users WHERE tel = '$receiver_tel'";
        $receiver_balance = 0;
        $result = $conn->query($get_receiver_sql);
        if ($result === false) {
            // Выводим сообщение об ошибке SQL
            echo "Error: " . $conn->error;
        } else {
            if ($result->num_rows > 0) {
                // Выводим данные каждой строки
                while ($row = $result->fetch_assoc()) {
                    $receiver_balance = $row["balance"];
                }
            } else {
                echo "0 results";
            }
        }

        // Update receiver's balance (add transfer amount)
        $new_receiver_balance = $receiver_balance + $transfer_amount;
        $update_receiver_sql = "UPDATE users SET balance = '$new_receiver_balance' WHERE tel = '$receiver_tel'";
        $conn ->query($update_receiver_sql);

        echo "<p style='text-align: center; background-color:#bceaa1; color: #54b91b; padding: 10px; width: fit-content'>Перевод успешен</p> <script>
            setTimeout(function(){
                window.location.href = 'main.php';
            }, 2000);
          </script>";
    } else {
        echo "<p style='text-align: center; background-color:#eaa1a1; color: #b91b1b; padding: 10px; width: fit-content'>Недостаточно средств</p>";

   echo "<script>
            setTimeout(function(){
                window.location.href = 'main.php';
            }, 2000);
          </script>";
    }

    // Close the connection
    $conn->close();
} else {
    echo "User telephone not found in session.";
}
exit();
?>
