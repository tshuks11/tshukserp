<?php
/**
 * Payment Model
 * Handles payment recording and tracking
 */
class Payment
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Record payment
     */
    public function record($data)
    {
        $this->db->beginTransaction();
        
        try {
            // Insert payment record
            $this->db->query(
                'INSERT INTO payments (invoice_id, customer_id, amount, payment_date, payment_method, reference, notes, user_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
                [$data['invoice_id'], $data['customer_id'], $data['amount'], $data['payment_date'], $data['payment_method'], $data['reference'] ?? null, $data['notes'] ?? null, $data['user_id']]
            );
            
            // Update invoice status
            $invoice = new Invoice();
            $inv = $invoice->getById($data['invoice_id']);
            
            $remainingAmount = $inv['total_amount'] - $data['amount'];
            if ($remainingAmount <= 0) {
                $invoice->updateStatus($data['invoice_id'], 'paid');
            } else {
                $invoice->updateStatus($data['invoice_id'], 'partially_paid');
            }
            
            // Update customer credit used
            $customer = new Customer();
            $cust = $customer->getById($data['customer_id']);
            $customer->update($data['customer_id'], ['credit_used' => $cust['credit_used'] - $data['amount']]);
            
            $this->db->commit();
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Get payments for invoice
     */
    public function getByInvoice($invoiceId)
    {
        $this->db->query('SELECT * FROM payments WHERE invoice_id = ? ORDER BY payment_date DESC', [$invoiceId]);
        return $this->db->fetchAll();
    }
    
    /**
     * Get all payments
     */
    public function getAll()
    {
        $this->db->query('SELECT p.*, i.reference as invoice_ref, c.name as customer_name FROM payments p JOIN invoices i ON p.invoice_id = i.id JOIN customers c ON p.customer_id = c.id ORDER BY p.created_at DESC');
        return $this->db->fetchAll();
    }
}
