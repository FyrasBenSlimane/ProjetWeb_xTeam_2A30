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
        
        if ($row) {
            // Decode any JSON fields
            if (isset($row->site_settings) && !empty($row->site_settings)) {
                $row->site_settings = json_decode($row->site_settings);
            }
            if (isset($row->mail_settings) && !empty($row->mail_settings)) {
                $row->mail_settings = json_decode($row->mail_settings);
            }
            if (isset($row->payment_settings) && !empty($row->payment_settings)) {
                $row->payment_settings = json_decode($row->payment_settings);
            }
            if (isset($row->security_settings) && !empty($row->security_settings)) {
                $row->security_settings = json_decode($row->security_settings);
            }
        }
        
        return $row ? $row : null; // Return the single row object or null
    }

    /**
     * Get a specific setting from a JSON settings group
     * 
     * @param string $group The settings group (site_settings, mail_settings, etc.)
     * @param string $key The specific setting key
     * @param mixed $default Default value if setting doesn't exist
     * @return mixed The setting value or default
     */
    public function getSetting($group, $key, $default = null) {
        $settings = $this->getAllSettings();
        
        if (!$settings || !isset($settings->$group)) {
            return $default;
        }
        
        $groupSettings = $settings->$group;
        
        if (is_string($groupSettings)) {
            $groupSettings = json_decode($groupSettings);
        }
        
        return isset($groupSettings->$key) ? $groupSettings->$key : $default;
    }

    /**
     * Updates site settings in the JSON column
     *
     * @param array $data Associative array of settings to update
     * @return bool True on success, false on failure
     */
    public function updateSiteSettings($data) {
        // Get current settings
        $settings = $this->getAllSettings();
        $currentSettings = isset($settings->site_settings) ? 
            (is_string($settings->site_settings) ? json_decode($settings->site_settings, true) : (array)$settings->site_settings) : 
            [];
        
        // Merge with new settings
        $updatedSettings = array_merge($currentSettings, $data);
        
        // Update the database
        $this->db->query('UPDATE settings SET site_settings = :site_settings WHERE id = 1');
        $this->db->bind(':site_settings', json_encode($updatedSettings));
        
        return $this->db->execute();
    }

    /**
     * Updates mail settings in the JSON column
     *
     * @param array $data Associative array of mail settings to update
     * @return bool True on success, false on failure
     */
    public function updateMailSettings($data) {
        // Get current settings
        $settings = $this->getAllSettings();
        $currentSettings = isset($settings->mail_settings) ? 
            (is_string($settings->mail_settings) ? json_decode($settings->mail_settings, true) : (array)$settings->mail_settings) : 
            [];
        
        // Merge with new settings
        $updatedSettings = array_merge($currentSettings, $data);
        
        // Update the database
        $this->db->query('UPDATE settings SET mail_settings = :mail_settings WHERE id = 1');
        $this->db->bind(':mail_settings', json_encode($updatedSettings));
        
        return $this->db->execute();
    }

    /**
     * Updates payment settings in the JSON column
     *
     * @param array $data Associative array of payment settings to update
     * @return bool True on success, false on failure
     */
    public function updatePaymentSettings($data) {
        // Get current settings
        $settings = $this->getAllSettings();
        $currentSettings = isset($settings->payment_settings) ? 
            (is_string($settings->payment_settings) ? json_decode($settings->payment_settings, true) : (array)$settings->payment_settings) : 
            [];
        
        // Merge with new settings
        $updatedSettings = array_merge($currentSettings, $data);
        
        // Update the database
        $this->db->query('UPDATE settings SET payment_settings = :payment_settings WHERE id = 1');
        $this->db->bind(':payment_settings', json_encode($updatedSettings));
        
        return $this->db->execute();
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
            // Check if value is an array or object and encode as JSON if needed
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
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