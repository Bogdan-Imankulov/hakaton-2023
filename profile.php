<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenAI Assistant Demo</title>
</head>
<body>

<form id="openai-form">
    <label for="user-input">Введите ваш запрос:</label>
    <input type="text" id="user-input" name="user-input" required>
    <button type="button" onclick="getOpenAIResponse()">Получить ответ</button>
</form>

<div id="response-container"></div>

<script>
    function getOpenAIResponse() {
        var userInput = document.getElementById('user-input').value;

        // Отправка запроса на сервер
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'openai_assistant.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var responseContainer = document.getElementById('response-container');
                responseContainer.innerHTML = '<p>Ответ OpenAI ассистента:</p><p>' + JSON.parse(xhr.responseText).response + '</p>';
            }
        };
        xhr.send('user_input=' + encodeURIComponent(userInput));
    }
</script>

</body>
</html>
