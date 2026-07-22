<?php
/**
 * AuditLog Model
 * Tracks all user actions for security and compliance
 */
class AuditLog
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Log action
     */
    public function log($action, $entityType = null, $entityId = null, $oldValues = null, $newValues = null)
    {
        $this->db->query(
            'INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
            [
                $_SESSION['user_id'] ?? null,
                $action,
                $entityType,
                $entityId,
                $oldValues ? json_encode($oldValues) : null,
                $newValues ? json_encode($newValues) : null,
                Security::getClientIP(),
                $_SERVER['HTTP_USER_AGENT'] ?? null,
            ]
        );
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Get all logs
     */
    public function getAll()
    {
        $this->db->query('SELECT a.*, u.name as user_name FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT 100');
        return $this->db->fetchAll();
    }
    
    /**
     * Get logs by user
     */
    public function getByUser($userId)
    {
        $this->db->query('SELECT * FROM audit_logs WHERE user_id = ? ORDER BY created_at DESC', [$userId]);
        return $this->db->fetchAll();
    }
}
