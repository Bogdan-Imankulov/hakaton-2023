<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Страница обратной связи</title>
</head>
<body>

<div class="container mt-5">
    <h2>Обратная связь</h2>
    <form action="toAdmin.php" method="post">
        <div class="form-group">
            <label for="phoneNumber">Номер телефона:</label>
            <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Введите номер телефона" required>
        </div>
        <div class="form-group">
            <label for="name">Имя:</label>
            <input type="text" class="form-control" id="name"  name="name" placeholder="Введите ваше имя" required>
        </div>
        <div class="form-group">
            <label for="question">Вопрос:</label>
            <textarea class="form-control" id="question"  name="question" rows="4" placeholder="Задайте ваш вопрос" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
