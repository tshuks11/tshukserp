<?php
/**
 * Expense Model
 * Handles operational expenses
 */
class Expense
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create expense
     */
    public function create($data)
    {
        $this->db->query(
            'INSERT INTO expenses (category, description, amount, expense_date, user_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
            [$data['category'], $data['description'] ?? null, $data['amount'], $data['expense_date'], $data['user_id']]
        );
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Get all expenses
     */
    public function getAll()
    {
        $this->db->query('SELECT * FROM expenses ORDER BY expense_date DESC');
        return $this->db->fetchAll();
    }
    
    /**
     * Get expenses by date range
     */
    public function getByDateRange($startDate, $endDate)
    {
        $this->db->query(
            'SELECT * FROM expenses WHERE expense_date BETWEEN ? AND ? ORDER BY expense_date DESC',
            [$startDate, $endDate]
        );
        return $this->db->fetchAll();
    }
    
    /**
     * Get total expenses by category
     */
    public function getTotalByCategory($startDate, $endDate)
    {
        $this->db->query(
            'SELECT category, SUM(amount) as total FROM expenses WHERE expense_date BETWEEN ? AND ? GROUP BY category',
            [$startDate, $endDate]
        );
        return $this->db->fetchAll();
    }
}
