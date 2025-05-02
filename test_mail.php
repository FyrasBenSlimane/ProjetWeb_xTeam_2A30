<?php
require_once 'config/mail.php';

$to = 'jadchida5@gmail.com'; // On envoie le test à votre adresse
$subject = 'Test - Configuration Email LenSi Events';
$message = "Bonjour,\n\nCeci est un email de test pour vérifier la configuration de l'envoi d'emails.\n\nSi vous recevez cet email, cela signifie que la configuration est réussie !\n\nCordialement,\nLenSi Events";

if(sendEmail($to, $subject, $message)) {
    echo "Email de test envoyé avec succès !";
} else {
    echo "Erreur lors de l'envoi de l'email de test. Vérifiez les logs pour plus de détails.";
}