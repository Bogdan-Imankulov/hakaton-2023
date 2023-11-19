<?php
$host = "localhost"; // например, "localhost"
$dbname = "p-338798_admin";
$user = "p-338798_admin";
$password = "p-338798_admin";

try {
    $tel = $_POST['phoneNumber'];
    // Подключение к базе данных с использованием PDO
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);

    // Установка режима обработки ошибок
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Подключение к базе данных установлено успешно";

    // Ваш код для выполнения SQL-запросов будет идти здесь

    // Например, создание таблицы
    $query = "SELECT * FROM users WHERE tel = :tel";
    $statement = $dbh->prepare($query);
    $statement->bindParam(":tel", $tel);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['search_result'] = $result;
    // Закрытие соединения
    $dbh = null;
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}