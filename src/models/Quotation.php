<?php
/**
 * Quotation Model
 * Handles quotation creation and management
 */
class Quotation
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create quotation
     */
    public function create($data)
    {
        $reference = generateUniqueCode('QT-');
        $verificationCode = generateUniqueCode('VER-');
        
        $this->db->query(
            'INSERT INTO quotations (reference, customer_id, user_id, total_amount, verification_code, expiry_date, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())',
            [$reference, $data['customer_id'], $data['user_id'], $data['total_amount'], $verificationCode, $data['expiry_date']]
        );
        
        return ['id' => $this->db->lastInsertId(), 'reference' => $reference, 'verification_code' => $verificationCode];
    }
    
    /**
     * Add item to quotation
     */
    public function addItem($quotationId, $productId, $quantity, $unitPrice)
    {
        $lineTotal = $quantity * $unitPrice;
        
        $this->db->query(
            'INSERT INTO quotation_items (quotation_id, product_id, quantity, unit_price, line_total, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
            [$quotationId, $productId, $quantity, $unitPrice, $lineTotal]
        );
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Get quotation by ID
     */
    public function getById($id)
    {
        $this->db->query('SELECT * FROM quotations WHERE id = ? LIMIT 1', [$id]);
        return $this->db->fetch();
    }
    
    /**
     * Get quotation items
     */
    public function getItems($quotationId)
    {
        $this->db->query(
            'SELECT qi.*, p.name, p.sku FROM quotation_items qi JOIN products p ON qi.product_id = p.id WHERE qi.quotation_id = ?',
            [$quotationId]
        );
        return $this->db->fetchAll();
    }
    
    /**
     * Update quotation status
     */
    public function updateStatus($id, $status)
    {
        $this->db->query('UPDATE quotations SET status = ?, updated_at = NOW() WHERE id = ?', [$status, $id]);
        return $this->db->rowCount();
    }
    
    /**
     * Get all quotations
     */
    public function getAll()
    {
        $this->db->query('SELECT q.*, c.name as customer_name FROM quotations q JOIN customers c ON q.customer_id = c.id ORDER BY q.created_at DESC');
        return $this->db->fetchAll();
    }
}
