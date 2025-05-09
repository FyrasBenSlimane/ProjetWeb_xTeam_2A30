<?php
/**
 * Script de test pour ajouter des notifications de candidatures en attente
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

// Ajouter des notifications de candidatures en attente (pending)
try {
    // Liste des projets fictifs
    $projects = [
        'Développement d\'une application mobile' => '500.00',
        'Création d\'un site vitrine' => '300.00',
        'Refonte d\'un site e-commerce' => '800.00',
        'Développement d\'un chatbot' => '450.00',
        'Intégration d\'une API de paiement' => '350.00'
    ];
    
    // Compteur pour les candidatures
    $i = 1;
    
    foreach ($projects as $projectTitle => $budget) {
        $title = "Candidature: $projectTitle";
        $message = "Vous avez soumis une candidature pour le projet \"$projectTitle\".";
        
        // Données pour la notification
        $data = [
            'candidature_id' => '10' . $i,
            'project_title' => $projectTitle,
            'status' => 'pending',
            'budget' => $budget
        ];
        
        // Ajouter la notification avec les bons paramètres
        $result = $notificationModel->addNotification(
            $userEmail,
            'candidature',
            $title,
            $message,
            '10' . $i,
            $data,
            null
        );
        
        if ($result) {
            echo "<p>Notification de candidature pour \"$projectTitle\" ajoutée avec succès.</p>";
        } else {
            echo "<p>Erreur lors de l'ajout de la notification pour \"$projectTitle\".</p>";
        }
        
        $i++;
    }
    
    echo "<p style='color: green;'>Toutes les notifications de candidatures en attente ont été ajoutées.</p>";
    echo "<p><a href='components/Dashboard/index.php?page=notifications'>Voir mes notifications</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur lors de l'ajout des notifications: " . $e->getMessage() . "</p>";
}
?> 