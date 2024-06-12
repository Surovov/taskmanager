<?php
// send_email.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        // Настройки сервера
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Укажите SMTP-сервер
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com'; // Ваш SMTP логин
        $mail->Password = 'your_email_password'; // Ваш SMTP пароль
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // От кого
        $mail->setFrom('from@example.com', 'Mailer');
        
        // Кому
        $mail->addAddress($to);

        // Содержание
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo 'Сообщение отправлено';
    } catch (Exception $e) {
        echo "Сообщение не может быть отправлено. Ошибка: {$mail->ErrorInfo}";
    }
}
?>
