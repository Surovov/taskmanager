<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: view/login-form.php?error=' . urlencode('Пожалуйста, войдите в систему.'));
    exit;
}
?>

