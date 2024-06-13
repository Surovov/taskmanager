<?php
session_start();

// Завершение сессии пользователя
session_unset();
session_destroy();

// Переадресация на страницу логина с алертом об успешном выходе
header('Location: ../view/login-form.php?message=' . urlencode('Вы успешно вышли из системы.'));
exit;
?>
