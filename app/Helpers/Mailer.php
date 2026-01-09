<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    /**
     * Send an email using PHPMailer
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body HTML body content
     * @return bool True on success, false on failure
     */
    public static function send(string $to, string $subject, string $body): bool
    {
        // SMTP configuration - ideally from environment variables
        $smtpHost     = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com'; // e.g. smtp.gmail.com
        $smtpUsername = $_ENV['SMTP_USER'] ?? 'tanggywarrior@gmail.com'; // Your email
        $smtpPassword = $_ENV['SMTP_PASS'] ?? 'xvpablgmbipwrtxw'; // App Password 
        $smtpPort     = $_ENV['SMTP_PORT'] ?? 587;
        $fromEmail    = $_ENV['MAIL_FROM_ADDRESS'] ?? 'support@wellcare.com';
        $fromName     = $_ENV['MAIL_FROM_NAME'] ?? 'Clinic Management System';

        $mail = new PHPMailer(true);

        try {
            // Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable for debugging
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUsername;
            $mail->Password   = $smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port       = $smtpPort;

            // Recipients
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body); // Plain text version

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log error for debugging
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
