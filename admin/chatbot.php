<?php
// Подключение к базе данных
$db = new mysqli('localhost', 'p-338798_admin',
    'p-338798_admin', 'p-338798_admin');

// Получение вопросов и ответов из базы данных
$result = $db->query("SELECT * FROM knowledge_base");

$knowledgeBase = [];
while ($row = $result->fetch_assoc()) {
    $knowledgeBase[$row['question']] = $row['answer'];
}

// Закрытие соединения с базой данных
$db->close();

// Получение сообщения от пользователя
$userMessage = trim($_POST['user_message']);

$botResponse = '';

if (isset($knowledgeBase[$userMessage])) {
    $botResponse = $knowledgeBase[$userMessage];
} else {
    $botResponse = "Извините, не могу ответить на этот вопрос.";
}



// Сохранение сообщения в базу данных
$db = new mysqli('localhost', 'p-338798_admin', 'p-338798_admin', 'p-338798_admin');
$db->query("INSERT INTO messages (user_message, bot_response) VALUES ('$userMessage', '$botResponse')");
$db->close();

// Возвращение ответа бота
echo $botResponse;
?>
