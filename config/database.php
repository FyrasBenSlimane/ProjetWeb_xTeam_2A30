<?php
class Database {
    private $host = "localhost";
    private $db_name = "lensi_db";
    private $username = "root";
    private $password = "";
    private $conn;

    // Obtenir la connexion
    public function getConnection() {
        $this->conn = null;

        try {
            // Vérifier si le serveur MySQL est accessible
            $socket = @fsockopen($this->host, 3306, $errno, $errstr, 5);
            if (!$socket) {
                throw new PDOException("Le serveur MySQL n'est pas accessible. Vérifiez que XAMPP est démarré et que MySQL est en cours d'exécution.");
            }
            @fclose($socket);

            // Tenter la connexion
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                )
            );
        } catch(PDOException $e) {
            // Message d'erreur plus détaillé et plus convivial
            $error_message = "Erreur de connexion à la base de données : \n";
            if (strpos($e->getMessage(), "refused") !== false) {
                $error_message .= "MySQL n'est pas démarré. Veuillez : \n";
                $error_message .= "1. Ouvrir XAMPP Control Panel\n";
                $error_message .= "2. Cliquer sur 'Start' pour MySQL\n";
                $error_message .= "3. Attendre que le service démarre\n";
                $error_message .= "4. Rafraîchir cette page";
            } else if (strpos($e->getMessage(), "Unknown database") !== false) {
                $error_message .= "La base de données 'lensi_db' n'existe pas. Veuillez :\n";
                $error_message .= "1. Ouvrir phpMyAdmin (http://localhost/phpmyadmin)\n";
                $error_message .= "2. Créer une nouvelle base de données nommée 'lensi_db'\n";
                $error_message .= "3. Importer le fichier database/lensi_db.sql";
            } else {
                $error_message .= $e->getMessage();
            }
            throw new PDOException($error_message);
        }

        return $this->conn;
    }

    // Fonction pour vérifier la connexion
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            return $conn !== null;
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}

// Créer une instance de la base de données
$database = new Database();
$db = $database->getConnection();