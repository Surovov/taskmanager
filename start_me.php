<?php
require 'vendor/db_connect.php'; // Подключение к базе данных

try {
    // Создание таблицы пользователей
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        reg_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Создание таблицы сброса пароля
    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX(created_at)
    )");

    // Создание таблицы задач
    $pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        deadline_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        status INT DEFAULT 1,
        image VARCHAR(255)
    )");

    echo "Таблицы успешно созданы.";
} catch (PDOException $e) {
    echo "Ошибка создания таблиц: " . $e->getMessage();
}
?>
