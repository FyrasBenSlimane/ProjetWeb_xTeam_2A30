<?php
require_once 'config/database.php';

$database = new Database();
if($database->testConnection()) {
    echo "<div style='color: green; padding: 20px; font-family: Arial;'>
            ✓ La connexion à la base de données est établie avec succès!
          </div>";
    
    // Test de la récupération des événements
    $query = "SELECT * FROM events LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        echo "<div style='color: green; padding: 20px; font-family: Arial;'>
                ✓ Les tables sont correctement configurées et contiennent des données!
              </div>";
    } else {
        echo "<div style='color: orange; padding: 20px; font-family: Arial;'>
                ⚠ La connexion fonctionne mais aucun événement n'a été trouvé. 
                Assurez-vous d'avoir importé les données d'exemple.
              </div>";
    }
} else {
    echo "<div style='color: red; padding: 20px; font-family: Arial;'>
            ✕ Erreur: Impossible de se connecter à la base de données. 
            Vérifiez vos paramètres de connexion dans config/database.php
          </div>";
}