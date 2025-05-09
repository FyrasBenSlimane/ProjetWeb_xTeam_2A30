<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    if ($db) {
        // Ajouter la colonne admin_notes si elle n'existe pas
        $sql = "ALTER TABLE participants ADD COLUMN IF NOT EXISTS admin_notes TEXT DEFAULT NULL";
        $db->exec($sql);
        echo "Connexion à la base de données réussie et structure mise à jour !";
        
        // Vérifier la structure de la table
        $result = $db->query("DESCRIBE participants");
        echo "<pre>\nStructure de la table participants :\n";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "{$row['Field']} - {$row['Type']}\n";
        }
        echo "</pre>";
    }
} catch (PDOException $e) {
    echo nl2br($e->getMessage());
}