<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../vendor/db_connect.php';
    require '../vendor/send_email.php';

    $email = $_POST['email'];

    // Проверка наличия email в базе данных
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Генерация токена
            $token = bin2hex(random_bytes(50));

            // Сохранение токена в базе данных
            $stmt = $pdo->prepare('INSERT INTO password_resets (email, token) VALUES (:email, :token)');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            // Отправка email с токеном
            $resetLink = getBaseUrl() . 'control/reset-password.php?token=' . $token;
            $message = 'Для восстановления пароля перейдите по ссылке: <a href="' . $resetLink . '">Восстановить пароль</a>';
            sendEmail($email, 'Восстановление пароля', $message);

            header('Location: ../view/forgot-form.php?message=' . urlencode('Ссылка для восстановления пароля отправлена на ваш email.'));
            exit;
        } else {
            $error = 'Пользователь с таким email не найден.';
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

// Функция для получения базового URL
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'] . '/';
    return $protocol . $domainName;
}
?>
