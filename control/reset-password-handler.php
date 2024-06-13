<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../vendor/db_connect.php';
    require '../vendor/send_email.php';

    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Проверка на незаполненные поля
    if (empty($password) || empty($confirmPassword)) {
        $error = 'Все поля обязательны для заполнения.';
        header('Location: ../view/reset-password-form.php?token=' . urlencode($token) . '&error=' . urlencode($error));
        exit;
    }

    // Проверка на совпадение паролей
    if ($password !== $confirmPassword) {
        $error = 'Пароли не совпадают.';
        header('Location: ../view/reset-password-form.php?token=' . urlencode($token) . '&error=' . urlencode($error));
        exit;
    }

    // Проверка на длину пароля
    if (strlen($password) < 6) {
        $error = 'Пароль должен быть не менее 6 символов.';
        header('Location: ../view/reset-password-form.php?token=' . urlencode($token) . '&error=' . urlencode($error));
        exit;
    }

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

            // Хэширование пароля
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Обновление пароля пользователя
            $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE email = :email');
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':email', $resetRequest['email']);
            $stmt->execute();

            // Удаление токена из базы данных
            $stmt = $pdo->prepare('DELETE FROM password_resets WHERE token = :token');
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            // Отправка уведомления о смене пароля
            sendEmail($resetRequest['email'], 'Пароль успешно изменен', 'Ваш пароль был успешно изменен.');

            // Запуск сессии и редирект на index.php
            session_start();
            $_SESSION['user_id'] = $resetRequest['email'];
            header('Location: ../index.php');
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
