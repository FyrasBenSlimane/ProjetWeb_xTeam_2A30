<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;
    private $connected = false;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5, // Set connection timeout to 5 seconds
            PDO::ATTR_EMULATE_PREPARES => false // Use real prepared statements
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            $this->connected = true;
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            // Log the error instead of displaying it directly
            error_log('Database Connection Error: ' . $this->error);
            
            // Format a more specific error message based on PDO error code
            $errorMessage = 'A database connection error occurred.';
            
            if (strpos($this->error, '[1045]') !== false) {
                $errorMessage .= ' Authentication failed - please check database credentials.';
            } elseif (strpos($this->error, '[2002]') !== false) {
                $errorMessage .= ' Cannot reach database server - please check host and network settings.';
            } elseif (strpos($this->error, '[1049]') !== false) {
                $errorMessage .= ' Database does not exist.';
            }
            
            // Display a user-friendly message
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
                echo $errorMessage . ' Please try again later or contact support.';
            } else {
                echo 'Connection Error: ' . $this->error . '<br>' . $errorMessage;
            }
        }
    }

    public function isConnected() {
        return $this->connected;
    }

    public function query($sql) {
        if (!$this->connected) {
            throw new Exception('Database connection not established');
        }
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (!$this->connected) {
            throw new Exception('Database connection not established');
        }
        
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        if (!$this->connected) {
            throw new Exception('Database connection not established');
        }
        return $this->stmt->execute();
    }

    public function resultSet() {
        if (!$this->connected) {
            throw new Exception('Database connection not established');
        }
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function single() {
        if (!$this->connected) {
            throw new Exception('Database connection not established');
        }
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function rowCount() {
        if (!$this->connected) {
            throw new Exception('Database connection not established');
        }
        return $this->stmt->rowCount();
    }

    public function lastInsertId() {
        if (!$this->connected) {
            throw new Exception('Database connection not established');
        }
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction() {
        if (!$this->connected) {
            throw new Exception('Database connection not established');
        }
        return $this->dbh->beginTransaction();
    }

    public function endTransaction() {
        if (!$this->connected) {
            throw new Exception('Database connection not established');
        }
        return $this->dbh->commit();
    }

    public function cancelTransaction() {
        if (!$this->connected) {
            throw new Exception('Database connection not established');
        }
        return $this->dbh->rollBack();
    }
}