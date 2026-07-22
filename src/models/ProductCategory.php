<?php
/**
 * ProductCategory Model
 * Manages product categories
 */
class ProductCategory
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all categories
     */
    public function getAll()
    {
        $this->db->query('SELECT * FROM product_categories ORDER BY name ASC');
        return $this->db->fetchAll();
    }
    
    /**
     * Get category by ID
     */
    public function getById($id)
    {
        $this->db->query('SELECT * FROM product_categories WHERE id = ? LIMIT 1', [$id]);
        return $this->db->fetch();
    }
    
    /**
     * Create category
     */
    public function create($name, $description = '')
    {
        $this->db->query(
            'INSERT INTO product_categories (name, description, created_at) VALUES (?, ?, NOW())',
            [$name, $description]
        );
        return $this->db->lastInsertId();
    }
}
