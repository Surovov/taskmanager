<?php
require '../vendor/auth.php'; // Подключение auth.php для проверки аутентификации
require '../vendor/db_connect.php';

// Получение ID задачи из URL
$taskId = $_GET['id'] ?? null;

if (!$taskId) {
    header('Location: ../index.php?error=' . urlencode('Неверный ID задачи.'));
    exit;
}

// Получение данных задачи из базы данных
try {
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        header('Location: ../index.php?error=' . urlencode('Задача не найдена.'));
        exit;
    }
} catch (PDOException $e) {
    die('Ошибка: ' . $e->getMessage());
}

// Удаление изображения
$imagePath = '../' . $task['image'];
if (!empty($task['image']) && file_exists($imagePath)) {
    unlink($imagePath);
}

// Обновление задачи в базе данных, удаление ссылки на изображение
try {
    $stmt = $pdo->prepare('UPDATE tasks SET image = NULL WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    header('Location: ../view/edit.php?id=' . $taskId . '&message=' . urlencode('Изображение удалено.'));
    exit;
} catch (PDOException $e) {
    die('Ошибка: ' . $e->getMessage());
}
?>
