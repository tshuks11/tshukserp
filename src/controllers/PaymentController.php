<?php
/**
 * Payment Controller
 * Handles payment recording
 */
class PaymentController extends BaseController
{
    /**
     * List all payments
     */
    public function index()
    {
        $this->authorize(PERM_MANAGE_PAYMENTS);
        
        $payment = new Payment();
        $payments = $payment->getAll();
        
        return $this->render('payments.index', [
            'payments' => $payments,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Record payment
     */
    public function create()
    {
        $this->authorize(PERM_MANAGE_PAYMENTS);
        
        $invoice = new Invoice();
        // Get unpaid invoices
        $this->db->query('SELECT i.id, i.reference, i.total_amount, i.status FROM invoices i WHERE i.status IN ("issued", "partially_paid") ORDER BY i.created_at DESC');
        $invoices = $this->db->fetchAll();
        
        return $this->render('payments.create', [
            'invoices' => $invoices,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Store payment
     */
    public function store()
    {
        $this->authorize(PERM_MANAGE_PAYMENTS);
        $this->validateCsrfToken();
        
        $payment = new Payment();
        $result = $payment->record([
            'invoice_id' => $_POST['invoice_id'],
            'customer_id' => $_POST['customer_id'],
            'amount' => $_POST['amount'],
            'payment_date' => $_POST['payment_date'],
            'payment_method' => $_POST['payment_method'],
            'reference' => $_POST['reference'] ?? null,
            'notes' => $_POST['notes'] ?? null,
            'user_id' => $_SESSION['user_id'],
        ]);
        
        if (!$result) {
            setFlash('error', 'Failed to record payment');
            redirect('/payments/create');
        }
        
        setFlash('success', 'Payment recorded successfully');
        redirect('/payments');
    }
}
