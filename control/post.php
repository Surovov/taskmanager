<?php
require '../vendor/auth.php'; // Подключение auth.php для проверки аутентификации
require '../vendor/db_connect.php';
require '../vendor/imagefolder.php';

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $title = $_POST['title'];
    $description = $_POST['description'] ?? null;
    $deadline_date = $_POST['deadline_date'] ?? null;
    $image = $_FILES['image'];

    // Валидация данных
    if (empty($title)) {
        $error = 'Заголовок обязателен для заполнения.';
        header('Location: ../index.php?error=' . urlencode($error));
        exit;
    }

    // Обработка изображения
    $imagePath = null;
    if (!empty($image['name'])) {
        $userDir = createUserImageFolder($_SESSION['user_id']);

        $imageExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $imagePath = $userDir . '/' . uniqid() . '.' . $imageExtension;

        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            $error = 'Ошибка загрузки изображения.';
            header('Location: ../index.php?error=' . urlencode($error));
            exit;
        }

        $imagePath = str_replace('../', '', $imagePath); // Убираем '../' для сохранения пути в базе данных
    }

    // Проверка и форматирование даты дедлайна
    if (empty($deadline_date)) {
        $deadline_date = null;
    }

    // Вставка задачи в базу данных
    try {
        $stmt = $pdo->prepare('INSERT INTO tasks (user_id, title, description, deadline_date, status, image) VALUES (:user_id, :title, :description, :deadline_date, 1, :image)');
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT); // Обеспечиваем, что user_id является целым числом
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        if ($deadline_date === null) {
            $stmt->bindValue(':deadline_date', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':deadline_date', $deadline_date);
        }
        $stmt->bindParam(':image', $imagePath);
        $stmt->execute();

        header('Location: ../index.php?message=' . urlencode('Задача успешно создана.'));
        exit;
    } catch (PDOException $e) {
        die('Ошибка: ' . $e->getMessage());
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>
