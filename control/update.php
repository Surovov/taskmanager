<?php
require '../vendor/auth.php'; // Подключение auth.php для проверки аутентификации
require '../vendor/db_connect.php';
require '../vendor/imagefolder.php';

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $taskId = $_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'] ?? null;
    $deadline_date = $_POST['deadline_date'] ?? null;
    $image = $_FILES['image'];

    // Валидация данных
    if (empty($title)) {
        $error = 'Заголовок обязателен для заполнения.';
        header('Location: ../view/edit.php?id=' . $taskId . '&error=' . urlencode($error));
        exit;
    }

    // Получение текущих данных задачи из базы данных
    try {
        $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = :id AND user_id = :user_id');
        $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            $error = 'Задача не найдена.';
            header('Location: ../view/edit.php?id=' . $taskId . '&error=' . urlencode($error));
            exit;
        }
    } catch (PDOException $e) {
        die('Ошибка: ' . $e->getMessage());
    }

    // Обработка изображения
    $imagePath = $task['image'];
    if (!empty($image['name'])) {
        $userDir = createUserImageFolder($_SESSION['user_id']);

        $imageExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $newImagePath = $userDir . '/' . uniqid() . '.' . $imageExtension;

        if (!move_uploaded_file($image['tmp_name'], $newImagePath)) {
            $error = 'Ошибка загрузки изображения.';
            header('Location: ../view/edit.php?id=' . $taskId . '&error=' . urlencode($error));
            exit;
        }

        $newImagePath = str_replace('../', '', $newImagePath); // Убираем '../' для сохранения пути в базе данных

        // Удаление старого изображения, если оно существует
        if (!empty($imagePath) && file_exists('../' . $imagePath)) {
            unlink('../' . $imagePath);
        }

        $imagePath = $newImagePath;
    }

    // Проверка и форматирование даты дедлайна
    if (empty($deadline_date)) {
        $deadline_date = null;
    }

    // Обновление задачи в базе данных
    try {
        $stmt = $pdo->prepare('UPDATE tasks SET title = :title, description = :description, deadline_date = :deadline_date, image = :image WHERE id = :id AND user_id = :user_id');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        if ($deadline_date === null) {
            $stmt->bindValue(':deadline_date', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':deadline_date', $deadline_date);
        }
        $stmt->bindParam(':image', $imagePath);
        $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        header('Location: ../index.php?message=' . urlencode('Задача успешно обновлена.'));
        exit;
    } catch (PDOException $e) {
        die('Ошибка: ' . $e->getMessage());
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>
