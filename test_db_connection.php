<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    if ($db) {
        echo "Connexion à la base de données réussie !";
    }
} catch (PDOException $e) {
    echo nl2br($e->getMessage());
}