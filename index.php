<?php
// Получаем пользовательский агент
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Проверяем, является ли устройство мобильным
$is_mobile = preg_match('/(android|iphone|ipad|ipod|blackberry|windows phone)/i', $user_agent);

// Если мобильное устройство, перенаправляем на login.php
if ($is_mobile) {
    header('Location: login.php');
    exit();
}

// Если не мобильное устройство, выводим содержимое страницы
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Страница недоступна</title>
  <!-- Подключение Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      margin-top: 100px;
      text-align: center;
    }

    .alert {
      max-width: 400px;
      margin: 0 auto;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Ошибка!</h4>
    <p>Данная страница не доступна на этом устройстве.</p>
    <hr>
    <p class="mb-0">Пожалуйста, используйте другое устройство для доступа к странице.</p>
  </div>
</div>

<!-- Подключение Bootstrap JS и Popper.js (необходимы для некоторых компонентов Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
