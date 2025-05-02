<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/mail.php';

echo "<pre>";
echo "Début du test d'envoi d'email...\n\n";

$to = 'jadchida5@gmail.com';
$subject = 'Test de Configuration Email - LenSi Events';
$message = "Bonjour,\n\nCeci est un email de test pour vérifier la configuration SMTP.\n\nCordialement,\nLenSi Events";

try {
    $result = sendEmail($to, $subject, $message);
    if ($result) {
        echo "Email envoyé avec succès!\n";
    } else {
        echo "Échec de l'envoi de l'email. Vérifiez les erreurs ci-dessus.\n";
    }
} catch (Exception $e) {
    echo "Une erreur s'est produite : " . $e->getMessage() . "\n";
}

echo "</pre>";