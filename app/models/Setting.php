<?php
class Setting {
    private $db;

    public function __construct(){
        // Assumes a Database class exists and provides a PDO instance
        $this->db = new Database;
    }

    /**
     * Fetches the single row of settings from the database.
     *
     * @return object|null The settings object or null if not found.
     */
    public function getAllSettings() {
        // Assuming there's only one row with id = 1 (or adjust as needed)
        $this->db->query('SELECT * FROM settings WHERE id = 1');
        $row = $this->db->single();
        return $row ? $row : null; // Return the single row object or null
    }

    /**
     * Updates the single row of settings in the database.
     *
     * @param array $data Associative array of settings columns to update [column_name => new_value].
     * @return bool True on success, false on failure.
     */
    public function updateSettings($data) {
        // Construct the SET part of the SQL query dynamically
        $setClauses = [];
        foreach (array_keys($data) as $key) {
            $setClauses[] = "`" . $key . "` = :" . $key;
        }
        $setClause = implode(', ', $setClauses);

        // Prepare the full SQL query
        // Assuming there's only one row with id = 1 (or adjust as needed)
        $sql = "UPDATE settings SET " . $setClause . " WHERE id = 1";
        $this->db->query($sql);

        // Bind all the values
        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }

        // Execute the update
        try {
            if ($this->db->execute()) {
                return true;
            } else {
                error_log("Failed to update settings.");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error updating settings: " . $e->getMessage());
            return false;
        }
    }

}
?>