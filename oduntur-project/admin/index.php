<?php
require_once 'db.php';

// Автоматически создаем таблицу, если её еще нет
$sql_create = "CREATE TABLE IF NOT EXISTS leads (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    tour VARCHAR(100) DEFAULT NULL,
    comment TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($sql_create);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $tour = $_POST['tour'] ?? '';
    $comment = $_POST['comment'] ?? '';

    if (!empty($name) && !empty($phone)) {
        $stmt = $conn->prepare("INSERT INTO leads (name, phone, tour, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $tour, $comment);
        
        if ($stmt->execute()) {
            $message = "<div style='color:green; padding:10px; border:1px solid green; background:#e8f5e9; margin-bottom:20px; border-radius:5px;'>Заявка добавлена!</div>";
        } else {
            $message = "<div style='color:red;'>Ошибка: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div style='color:red;'>Заполните имя и телефон.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тестовая форма</title>
    <style>body{font-family:sans-serif; background:#f4efe6; padding:40px;} .card{background:#fff; max-width:500px; padding:30px; border-radius:10px;}</style>
</head>
<body>
    <div class="card">
        <h2>Форма заявки</h2>
        <?= $message ?>
        <form method="POST">
            <p>Имя:<br><input type="text" name="name" required style="width:100%; padding:8px;"></p>
            <p>Телефон:<br><input type="text" name="phone" required style="width:100%; padding:8px;"></p>
            <p>Тур:<br><input type="text" name="tour" style="width:100%; padding:8px;"></p>
            <p>Комментарий:<br><textarea name="comment" style="width:100%; padding:8px;"></textarea></p>
            <button type="submit" style="background:#C77B3F; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">Отправить</button>
        </form>
        <p style="margin-top:20px;"><a href="admin.php">Перейти в админку →</a></p>
    </div>
</body>
</html>