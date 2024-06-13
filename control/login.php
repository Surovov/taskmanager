<?php
session_start();

// Подключаем файл для соединения с базой данных и отправки e-mail
require '../vendor/db_connect.php';
require '../vendor/send_email.php';

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверка на пустоту данных
    if (empty($email) || empty($password)) {
        $error = 'Все поля обязательны для заполнения.';
        header('Location: ../view/login-form.php?error=' . urlencode($error));
        exit;
    }

    // Проверка наличия пользователя с таким e-mail
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Проверка пароля
            if (password_verify($password, $user['password'])) {
                // Успешная авторизация, сбрасываем счетчик неудачных попыток
                unset($_SESSION['failed_attempts']);
                
                // Запуск сессии и редирект на index.php
                $_SESSION['user_id'] = $user['id'];
                header('Location: ../index.php');
                exit;
            } else {
                // Обработка неудачной попытки
                if (!isset($_SESSION['failed_attempts'])) {
                    $_SESSION['failed_attempts'] = 0;
                }
                $_SESSION['failed_attempts'] += 1;

                // Проверка количества неудачных попыток
                if ($_SESSION['failed_attempts'] >= 15) {
                    // Заблокировать доступ и отправить уведомление
                    $resetLink = 'http://yourdomain.com/view/forgot-form.php';
                    $message = 'Было зафиксировано слишком много неудачных попыток входа в ваш аккаунт. Если это были не вы, рекомендуем <a href="' . $resetLink . '">сменить пароль</a>.';
                    sendEmail($email, 'Уведомление о попытке входа', $message);
                    $error = 'Ваш аккаунт временно заблокирован из-за слишком большого количества неудачных попыток входа. Восстановить пароль <a href="../view/forgot-form.php">можно здесь</a>.';
                    header('Location: ../view/login-form.php?error=' . urlencode($error));
                    exit;
                } else {
                    $error = 'Неправильный пароль.';
                    header('Location: ../view/login-form.php?error=' . urlencode($error));
                    exit;
                }
            }
        } else {
            $error = 'Пользователь с таким e-mail не найден.';
            header('Location: ../view/login-form.php?error=' . urlencode($error));
            exit;
        }
    } catch (PDOException $e) {
        die('Ошибка: ' . $e->getMessage());
    }
} else {
    header('Location: ../view/login-form.php');
    exit;
}
?>
