<?php
// send_email.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/autoload.php'; // Composer autoloader

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        // Настройки сервера
        $mail->isSMTP();
        $mail->Host = ''; // Укажите SMTP-сервер
        $mail->SMTPAuth = true;
        $mail->Username = ''; // Ваш SMTP логин
        $mail->Password = ''; // Ваш SMTP пароль
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525; // Советую перепроверить, так как может отличаться

        // От кого
        $mail->setFrom('', 'Mailer');
        
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
