<?php
/**
 * Company Settings Model
 * Manages company branding and settings
 */
class CompanySettings
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all settings
     */
    public function getAll()
    {
        $this->db->query('SELECT * FROM company_settings');
        $results = $this->db->fetchAll();
        
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
    
    /**
     * Get setting by key
     */
    public function get($key, $default = null)
    {
        $this->db->query('SELECT value FROM company_settings WHERE key = ? LIMIT 1', [$key]);
        $result = $this->db->fetch();
        return $result ? $result['value'] : $default;
    }
    
    /**
     * Update setting
     */
    public function set($key, $value)
    {
        $this->db->query(
            'INSERT INTO company_settings (key, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?',
            [$key, $value, $value]
        );
        return $this->db->rowCount();
    }
}
