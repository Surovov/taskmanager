<?php
require '../vendor/auth.php'; // Подключение auth.php для проверки аутентификации
require '../vendor/db_connect.php'; // Подключение к базе данных

$isAjaxRequest = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $taskId = $data['id'] ?? $_POST['task_id'] ?? null;

    if (is_null($taskId) || !is_numeric($taskId)) {
        $error = 'Неверный идентификатор задачи.';
        if ($isAjaxRequest) {
            echo json_encode(['success' => false, 'error' => $error]);
        } else {
            header('Location: ../index.php?error=' . urlencode($error));
        }
        exit;
    }

    try {
        $stmt = $pdo->prepare('UPDATE tasks SET status = 3 WHERE id = :task_id AND user_id = :user_id');
        $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = 'Задача успешно архивирована.';
            if ($isAjaxRequest) {
                echo json_encode(['success' => true, 'message' => $message]);
            } else {
                header('Location: ../index.php?message=' . urlencode($message));
            }
        } else {
            $error = 'Не удалось архивировать задачу.';
            if ($isAjaxRequest) {
                echo json_encode(['success' => false, 'error' => $error]);
            } else {
                header('Location: ../index.php?error=' . urlencode($error));
            }
        }
        exit;
    } catch (PDOException $e) {
        $error = 'Ошибка: ' . $e->getMessage();
        if ($isAjaxRequest) {
            echo json_encode(['success' => false, 'error' => $error]);
        } else {
            die($error);
        }
        exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>
