<?php
// Проверяем, что запрос пришел методом POST, а не кто то открыл файл или сделал $_GET запрос. 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../vendor/db_connect.php'; // подключил конектор к бд

    // присвоил данные из формы, хранящиеся в $_POST к переменным. 
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // проверка на незаполненные поля, с callback-ом в register-form.php об ошибке
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Все поля обязательны для заполнения.';
        header('Location: ../view/register-form.php?error=' . urlencode($error));
        exit;
    }
    // проверка на короткий член, с callback-ом в register-form.php об ошибке
    if (strlen($password) < 6) {
        $error = 'Пароль должен быть не менее 6 символов.';
        header('Location: ../view/register-form.php?error=' . urlencode($error));
        exit;
    }

    // SQL запрос с поиском такого e-mail и логика с проверкой уже существующего такого с callback алертом в форму
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $error = 'Пользователь с таким e-mail уже существует.';
            header('Location: ../view/register-form.php?error=' . urlencode($error));
            exit;
        }
    } catch (PDOException $e) {
        die('Ошибка: ' . $e->getMessage());
    }

    // Захешировал пароль
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // создал юзера
    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Запуск сессии и редирект на index.php
        session_start();
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: ../index.php');
        exit;
    } catch (PDOException $e) {
        die('Ошибка: ' . $e->getMessage());
    }


} else {
    // Если метод запроса не POST, перенаправляем на форму регистрации
    header('Location: ../view/register-form.php');
    exit;
}