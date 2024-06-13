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
        $mail->Host = 'smtp.beget.com'; // Укажите SMTP-сервер
        $mail->SMTPAuth = true;
        $mail->Username = 'taskmanager@surovov.com'; // Ваш SMTP логин
        $mail->Password = 'LMvD7naGgz%a'; // Ваш SMTP пароль
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;

        // От кого
        $mail->setFrom('taskmanager@surovov.com', 'Mailer');
        
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
