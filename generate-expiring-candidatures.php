<?php
/**
 * Script de test pour ajouter des notifications de candidatures avec délai d'expiration
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
        // Utiliser la fonction ensureTableExists du modèle
        $notificationModel = new NotificationModel($db);
        echo "<p>Vérification de la table notifications effectuée.</p>";
    } else {
        echo "<p>Erreur: Impossible de se connecter à la base de données.</p>";
        exit;
    }
} catch (PDOException $e) {
    echo "<p>Erreur lors de la vérification de la table: " . $e->getMessage() . "</p>";
}

// Ajouter des notifications de candidatures avec différents délais d'expiration
try {
    // Notification de candidature qui expire dans 1 jour et 23 heures
    $expiresIn1Day = (new DateTime())->add(new DateInterval('P1DT23H'));
    $result1 = $notificationModel->addNotification(
        $userEmail,
        'candidature',
        'Candidature: NIDS',
        'Vous avez soumis une candidature pour le projet "NIDS". Elle est actuellement en attente.',
        '101',
        [
            'candidature_id' => '101',
            'project_title' => 'NIDS',
            'status' => 'pending',
            'budget' => '200.00',
            'expires_at' => $expiresIn1Day->format('Y-m-d H:i:s')
        ],
        $expiresIn1Day->format('Y-m-d H:i:s')
    );
    
    if ($result1) {
        echo "<p>Notification 1 ajoutée - Expire dans 1 jour et 23 heures</p>";
    }
    
    // Notification de candidature qui expire dans 12 heures
    $expiresIn12Hours = (new DateTime())->add(new DateInterval('PT12H'));
    $result2 = $notificationModel->addNotification(
        $userEmail,
        'candidature',
        'Candidature: Développement Mobile',
        'Vous avez soumis une candidature pour le projet "Développement Mobile". Elle est actuellement en attente.',
        '102',
        [
            'candidature_id' => '102',
            'project_title' => 'Développement Mobile',
            'status' => 'pending',
            'budget' => '150.00',
            'expires_at' => $expiresIn12Hours->format('Y-m-d H:i:s')
        ],
        $expiresIn12Hours->format('Y-m-d H:i:s')
    );
    
    if ($result2) {
        echo "<p>Notification 2 ajoutée - Expire dans 12 heures</p>";
    }
    
    // Notification de candidature qui expire dans 5 heures
    $expiresIn5Hours = (new DateTime())->add(new DateInterval('PT5H'));
    $result3 = $notificationModel->addNotification(
        $userEmail,
        'candidature',
        'Candidature: Design UX/UI',
        'Vous avez soumis une candidature pour le projet "Design UX/UI". Elle est actuellement en attente.',
        '103',
        [
            'candidature_id' => '103',
            'project_title' => 'Design UX/UI',
            'status' => 'pending',
            'budget' => '300.00',
            'expires_at' => $expiresIn5Hours->format('Y-m-d H:i:s')
        ],
        $expiresIn5Hours->format('Y-m-d H:i:s')
    );
    
    if ($result3) {
        echo "<p>Notification 3 ajoutée - Expire dans 5 heures</p>";
    }
    
    // Notification de candidature presque expirée (1 heure)
    $expiresIn1Hour = (new DateTime())->add(new DateInterval('PT1H'));
    $result4 = $notificationModel->addNotification(
        $userEmail,
        'candidature',
        'Candidature: Application Web',
        'Vous avez soumis une candidature pour le projet "Application Web". Elle est actuellement en attente.',
        '104',
        [
            'candidature_id' => '104',
            'project_title' => 'Application Web',
            'status' => 'pending',
            'budget' => '250.00',
            'expires_at' => $expiresIn1Hour->format('Y-m-d H:i:s')
        ],
        $expiresIn1Hour->format('Y-m-d H:i:s')
    );
    
    if ($result4) {
        echo "<p>Notification 4 ajoutée - Expire dans 1 heure</p>";
    }
    
    echo "<p style='color: green;'>Notifications de candidatures avec délais d'expiration ajoutées avec succès!</p>";
    echo "<p><a href='components/Dashboard/index.php?page=notifications'>Voir mes notifications</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur lors de l'ajout des notifications: " . $e->getMessage() . "</p>";
}
?>