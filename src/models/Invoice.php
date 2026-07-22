<?php
/**
 * Invoice Model
 * Handles invoice creation and management
 */
class Invoice
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create invoice from quotation
     */
    public function createFromQuotation($quotationId, $userId)
    {
        $quotation = new Quotation();
        $q = $quotation->getById($quotationId);
        
        if (!$q) {
            return false;
        }
        
        $reference = generateUniqueCode('INV-');
        $verificationCode = generateUniqueCode('VER-');
        
        $this->db->beginTransaction();
        
        try {
            $this->db->query(
                'INSERT INTO invoices (reference, quotation_id, customer_id, user_id, total_amount, verification_code, issue_date, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
                [$reference, $quotationId, $q['customer_id'], $userId, $q['total_amount'], $verificationCode, date('Y-m-d'), 'issued']
            );
            
            $invoiceId = $this->db->lastInsertId();
            
            // Copy items from quotation
            $items = $quotation->getItems($quotationId);
            foreach ($items as $item) {
                $this->db->query(
                    'INSERT INTO invoice_items (invoice_id, product_id, quantity, unit_price, line_total, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
                    [$invoiceId, $item['product_id'], $item['quantity'], $item['unit_price'], $item['line_total']]
                );
                
                // Deduct stock
                $product = new Product();
                $product->updateStock($item['product_id'], -$item['quantity']);
                
                // Log stock movement
                $this->logStockMovement($item['product_id'], 'out', $item['quantity'], 'sale', $invoiceId, $userId);
            }
            
            // Update quotation status
            $quotation->updateStatus($quotationId, 'accepted');
            
            $this->db->commit();
            
            return ['id' => $invoiceId, 'reference' => $reference, 'verification_code' => $verificationCode];
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Create invoice directly
     */
    public function create($data)
    {
        $reference = generateUniqueCode('INV-');
        $verificationCode = generateUniqueCode('VER-');
        
        $this->db->query(
            'INSERT INTO invoices (reference, customer_id, user_id, total_amount, verification_code, issue_date, due_date, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
            [$reference, $data['customer_id'], $data['user_id'], $data['total_amount'], $verificationCode, $data['issue_date'], $data['due_date'], 'draft']
        );
        
        return ['id' => $this->db->lastInsertId(), 'reference' => $reference, 'verification_code' => $verificationCode];
    }
    
    /**
     * Add item to invoice
     */
    public function addItem($invoiceId, $productId, $quantity, $unitPrice)
    {
        $lineTotal = $quantity * $unitPrice;
        
        $this->db->query(
            'INSERT INTO invoice_items (invoice_id, product_id, quantity, unit_price, line_total, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
            [$invoiceId, $productId, $quantity, $unitPrice, $lineTotal]
        );
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Get invoice by ID
     */
    public function getById($id)
    {
        $this->db->query('SELECT * FROM invoices WHERE id = ? LIMIT 1', [$id]);
        return $this->db->fetch();
    }
    
    /**
     * Get invoice items
     */
    public function getItems($invoiceId)
    {
        $this->db->query(
            'SELECT ii.*, p.name, p.sku FROM invoice_items ii JOIN products p ON ii.product_id = p.id WHERE ii.invoice_id = ?',
            [$invoiceId]
        );
        return $this->db->fetchAll();
    }
    
    /**
     * Update invoice status
     */
    public function updateStatus($id, $status)
    {
        $this->db->query('UPDATE invoices SET status = ?, updated_at = NOW() WHERE id = ?', [$status, $id]);
        return $this->db->rowCount();
    }
    
    /**
     * Get all invoices
     */
    public function getAll()
    {
        $this->db->query('SELECT i.*, c.name as customer_name FROM invoices i JOIN customers c ON i.customer_id = c.id ORDER BY i.created_at DESC');
        return $this->db->fetchAll();
    }
    
    /**
     * Log stock movement
     */
    private function logStockMovement($productId, $type, $quantity, $refType, $refId, $userId)
    {
        $this->db->query(
            'INSERT INTO stock_movements (product_id, movement_type, quantity, reference_type, reference_id, user_id, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())',
            [$productId, $type, $quantity, $refType, $refId, $userId]
        );
    }
}
