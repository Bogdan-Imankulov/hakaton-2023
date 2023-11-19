<?php
chatGPT('привет');
function chatGPT($prompt)
{
    $url = "https://api.openai.com/v1/chat/completions";  // Update the endpoint
    $apiKey = "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";
    $prompt = "привет";

// Формирование данных для отправки в API
    $data = [
        'model' => 'text-davinci-003',  // Выбор конкретной модели
        'prompt' => $prompt,
        'max_tokens' => 150  // Максимальное количество токенов в ответе
    ];

// Формирование HTTP-заголовков
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ];

// Формирование параметров контекста для HTTP-запроса
    $options = [
        'http' => [
            'header' => implode("\r\n", $headers),
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];

// Создание контекста для HTTP-запроса
    $context = stream_context_create($options);

// Отправка запроса к API OpenAI
    $response = file_get_contents('https://api.openai.com/v1/engines/text-davinci-003/completions', false, $context);

// Обработка ответа
    if ($response === false) {
        die('Ошибка при отправке запроса к API OpenAI');
    }

// Декодирование JSON-ответа
    $result = json_decode($response, true);

// Вывод ответа от модели
    echo $result['choices'][0]['text'];
}
    ?>