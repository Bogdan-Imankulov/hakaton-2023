<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Bot</title>
    <!-- Подключение Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        /* Дополнительные стили для вашего контента (необязательно) */
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        #chat {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mt-5 mb-4 text-center">Chat Bot</h1>
    <div id="chat" class="bg-light p-3 rounded">
        <div id="chat-box"></div>
        <input type="text" id="user-input" class="form-control mb-2" placeholder="Введите ваш вопрос">
        <button onclick="sendMessage()" class="btn btn-primary">Отправить</button>
    </div>
</div>

<!-- Подключение Bootstrap JS (необходимо для работы интерактивных элементов) -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    let incorrectQuestionCount = 0;
    var chatBox = document.getElementById("chat-box");
    function sendMessage() {
        if (incorrectQuestionCount > 1) {
            chatBox.innerHTML = "<p>Сейчас вас переведут на форму для отправки сообщения менеджеру</p>"
            setTimeout(function () {
                window.location.href = '../pr.php'
            }, 3000)
        } else {
            var userMessage = document.getElementById("user-input").value;

            // Отправка запроса на сервер
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Обновление чата
                    chatBox.innerHTML += "<p>Вы: " + userMessage + "<br>Помощник: " + xhr.responseText + "</p>";
                    if (xhr.responseText === "Извините, не могу ответить на этот вопрос.") {
                        incorrectQuestionCount++;
                        console.log(incorrectQuestionCount)
                    }
                }
            };
            xhr.open("POST", "chatbot.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("user_message=" + userMessage);

            // Очистка поля ввода
            document.getElementById("user-input").value = "";
        }
    }
</script>
</body>
</html>