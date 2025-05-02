<?php
require_once '../config/database.php';

try {
    // Vérifier si le compte admin existe déjà
    $stmt = $db->prepare("SELECT * FROM admins WHERE username = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    // Créer le compte admin s'il n'existe pas
    if (!$admin) {
        $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $password_hash, 'admin@lensi.com']);
    }
} catch(PDOException $e) {
    error_log("Erreur de base de données: " . $e->getMessage());
}