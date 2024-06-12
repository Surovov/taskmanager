<?php
// db_connect.php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=taskmanager', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}
?>
