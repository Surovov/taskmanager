<?php
require '../vendor/auth.php'; // Подключение auth.php для проверки аутентификации
require '../vendor/db_connect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskId = $_POST['task_id'] ?? null;
    $redirectUrl = $_POST['redirect_url'] ?? '../index.php';

    if (is_null($taskId) || !is_numeric($taskId)) {
        $error = 'Неверный идентификатор задачи.';
        header('Location: ' . $redirectUrl . '?error=' . urlencode($error));
        exit;
    }

    try {
        // Проверка, существует ли задача с таким id
        $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = :task_id AND user_id = :user_id');
        $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            // Удаление изображения, если оно существует
            if (!empty($task['image'])) {
                $imagePath = '../' . $task['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Удаление задачи
            $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = :task_id AND user_id = :user_id');
            $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            $message = 'Задача успешно удалена.';
            header('Location: ' . $redirectUrl . '?message=' . urlencode($message));
        } else {
            $error = 'Задача не найдена.';
            header('Location: ' . $redirectUrl . '?error=' . urlencode($error));
        }
        exit;
    } catch (PDOException $e) {
        die('Ошибка: ' . $e->getMessage());
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>
