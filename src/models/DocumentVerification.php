<?php
/**
 * DocumentVerification Model
 * Handles QR code verification for documents
 */
class DocumentVerification
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Verify document by code
     */
    public function verifyByCode($code)
    {
        // Check quotations
        $this->db->query('SELECT id, reference, "quotation" as type, total_amount, created_at FROM quotations WHERE verification_code = ? LIMIT 1', [$code]);
        $document = $this->db->fetch();
        
        if ($document) {
            $this->logVerification($code, 'quotation', $document['id']);
            return $document;
        }
        
        // Check invoices
        $this->db->query('SELECT id, reference, "invoice" as type, total_amount, created_at FROM invoices WHERE verification_code = ? LIMIT 1', [$code]);
        $document = $this->db->fetch();
        
        if ($document) {
            $this->logVerification($code, 'invoice', $document['id']);
            return $document;
        }
        
        return null;
    }
    
    /**
     * Get verification details
     */
    public function getDetails($type, $id)
    {
        if ($type === 'quotation') {
            $this->db->query('SELECT q.*, c.name as customer_name FROM quotations q JOIN customers c ON q.customer_id = c.id WHERE q.id = ?', [$id]);
            $document = $this->db->fetch();
            
            if ($document) {
                $this->db->query('SELECT qi.*, p.name as product_name FROM quotation_items qi JOIN products p ON qi.product_id = p.id WHERE qi.quotation_id = ?', [$id]);
                $document['items'] = $this->db->fetchAll();
            }
            
            return $document;
        } elseif ($type === 'invoice') {
            $this->db->query('SELECT i.*, c.name as customer_name FROM invoices i JOIN customers c ON i.customer_id = c.id WHERE i.id = ?', [$id]);
            $document = $this->db->fetch();
            
            if ($document) {
                $this->db->query('SELECT ii.*, p.name as product_name FROM invoice_items ii JOIN products p ON ii.product_id = p.id WHERE ii.invoice_id = ?', [$id]);
                $document['items'] = $this->db->fetchAll();
            }
            
            return $document;
        }
        
        return null;
    }
    
    /**
     * Log verification attempt
     */
    private function logVerification($code, $type, $id)
    {
        $this->db->query(
            'INSERT INTO print_logs (document_type, document_id, verification_code, print_count, created_at) VALUES (?, ?, ?, 1, NOW()) ON DUPLICATE KEY UPDATE print_count = print_count + 1, last_printed_at = NOW()',
            [$type, $id, $code]
        );
    }
}
