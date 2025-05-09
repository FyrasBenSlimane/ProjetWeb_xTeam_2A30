<?php
/**
 * Script de test pour ajouter des notifications
 */

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['email'])) {
    echo "Vous devez être connecté pour utiliser ce script.";
    exit;
}

// Informations utilisateur
$userEmail = $_SESSION['user']['email'];
$userName = $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'];

// Inclure les fichiers nécessaires
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/components/Dashboard/models/NotificationModel.php';

// Essayer de créer la table notifications si elle n'existe pas
try {
    $db = $GLOBALS['pdo'] ?? null;
    if ($db) {
        $sql = "CREATE TABLE IF NOT EXISTS `notifications` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_email` varchar(255) NOT NULL,
            `type` varchar(50) NOT NULL,
            `message` text NOT NULL,
            `data` text DEFAULT NULL,
            `linked_id` varchar(255) DEFAULT NULL,
            `is_read` tinyint(1) NOT NULL DEFAULT 0,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            KEY `user_email` (`user_email`),
            KEY `is_read` (`is_read`),
            KEY `created_at` (`created_at`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        
        $db->exec($sql);
        echo "<p>Vérification de la table notifications effectuée.</p>";
    } else {
        echo "<p>Erreur: Impossible de se connecter à la base de données.</p>";
        exit;
    }
} catch (PDOException $e) {
    echo "<p>Erreur lors de la création de la table: " . $e->getMessage() . "</p>";
}

// Créer le modèle de notification
$notificationModel = new NotificationModel($db);

// Ajouter quelques notifications test
try {
    // Notification de projet
    $notificationModel->addNotification(
        $userEmail,
        'project',
        'Un nouveau projet correspondant à vos compétences est disponible!',
        ['project_title' => 'Développement d\'un site e-commerce'],
        '123'
    );
    
    // Notification de candidature acceptée
    $notificationModel->addNotification(
        $userEmail,
        'candidature',
        'Votre candidature pour le projet "Design d\'interface mobile" a été acceptée.',
        [
            'candidature_id' => '456',
            'project_title' => 'Design d\'interface mobile',
            'status' => 'accepted'
        ],
        '456'
    );
    
    // Notification de candidature rejetée
    $notificationModel->addNotification(
        $userEmail,
        'candidature',
        'Votre candidature pour le projet "Création d\'une API REST" a été rejetée.',
        [
            'candidature_id' => '789',
            'project_title' => 'Création d\'une API REST',
            'status' => 'rejected'
        ],
        '789'
    );
    
    // Notification de message
    $notificationModel->addNotification(
        $userEmail,
        'message',
        'Vous avez reçu un nouveau message de Jean Dupont.',
        ['sender_name' => 'Jean Dupont'],
        '101'
    );
    
    // Notification système
    $notificationModel->addNotification(
        $userEmail,
        'system',
        'Votre compte a été vérifié avec succès.',
        [],
        null
    );
    
    echo "<p>5 notifications test ont été ajoutées avec succès!</p>";
    echo "<p><a href='components/Dashboard/index.php'>Retourner au tableau de bord</a></p>";
    
} catch (Exception $e) {
    echo "<p>Erreur lors de l'ajout des notifications: " . $e->getMessage() . "</p>";
}
?> 