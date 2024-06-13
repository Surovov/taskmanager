<?php
require '../vendor/db_connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Проверка токена в базе данных
    try {
        $stmt = $pdo->prepare('SELECT * FROM password_resets WHERE token = :token');
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resetRequest) {
            // Проверка времени создания токена
            $tokenAge = time() - strtotime($resetRequest['created_at']);
            if ($tokenAge > 3600) { // 3600 секунд = 1 час
                $error = 'Токен истек. Пожалуйста, запросите восстановление пароля снова.';
                header('Location: ../view/forgot-form.php?error=' . urlencode($error));
                exit;
            }

            // Токен найден и действителен, редирект на форму смены пароля с передачей токена
            header('Location: ../view/reset-password-form.php?token=' . urlencode($token));
            exit;
        } else {
            $error = 'Неверный токен. Пожалуйста, попробуйте снова.';
            header('Location: ../view/forgot-form.php?error=' . urlencode($error));
            exit;
        }
    } catch (PDOException $e) {
        die('Ошибка: ' . $e->getMessage());
    }
} else {
    header('Location: ../view/forgot-form.php');
    exit;
}
?>
