<?php
require_once __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jadchida5@gmail.com';
        $mail->Password = 'bqby yrxh hhmo gjvz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Options SSL pour XAMPP
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Paramètres d'envoi
        $mail->setFrom('jadchida5@gmail.com', 'LenSi Events');
        $mail->addAddress($to);
        
        // Support des images et HTML
        $mail->isHTML(true);
        
        // Convertir les balises img en images inline
        if(preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $message, $matches)) {
            foreach($matches[1] as $i => $imagePath) {
                if(strpos($imagePath, 'http') !== 0) {
                    // Image locale
                    $absolutePath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;
                    if(file_exists($absolutePath)) {
                        $cid = 'image' . $i;
                        $mail->addEmbeddedImage($absolutePath, $cid);
                        $message = str_replace($imagePath, 'cid:' . $cid, $message);
                    }
                }
            }
        }
        
        // Contenu
        $mail->Subject = $subject;
        $mail->Body = nl2br($message);
        $mail->AltBody = strip_tags($message);

        // Envoi
        return $mail->send();
    } catch (Exception $e) {
        error_log("Erreur Mailer : " . $mail->ErrorInfo);
        return false;
    }
}