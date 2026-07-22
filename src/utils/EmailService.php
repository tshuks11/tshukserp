<?php
/**
 * Email Service
 * Handles email notifications
 */
class EmailService
{
    private $from = '';
    private $fromName = '';
    
    public function __construct()
    {
        $this->from = 'noreply@tshukserp.com';
        $this->fromName = APP_NAME;
    }
    
    /**
     * Send quotation notification
     */
    public function sendQuotationNotification($quotationId, $toEmail)
    {
        $quotation = new Quotation();
        $q = $quotation->getById($quotationId);
        
        $subject = 'Quotation ' . $q['reference'];
        $body = "Dear Customer,\n\n";
        $body .= "You have received a new quotation.\n\n";
        $body .= "Quotation Reference: " . $q['reference'] . "\n";
        $body .= "Amount: " . formatCurrency($q['total_amount']) . "\n";
        $body .= "Valid Until: " . formatDate($q['expiry_date']) . "\n\n";
        $body .= "Verification Code: " . $q['verification_code'] . "\n\n";
        $body .= "Thank you!\n";
        
        return $this->send($toEmail, $subject, $body);
    }
    
    /**
     * Send invoice notification
     */
    public function sendInvoiceNotification($invoiceId, $toEmail)
    {
        $invoice = new Invoice();
        $i = $invoice->getById($invoiceId);
        
        $subject = 'Invoice ' . $i['reference'];
        $body = "Dear Customer,\n\n";
        $body .= "Your invoice has been issued.\n\n";
        $body .= "Invoice Reference: " . $i['reference'] . "\n";
        $body .= "Amount: " . formatCurrency($i['total_amount']) . "\n";
        $body .= "Due Date: " . formatDate($i['due_date']) . "\n\n";
        $body .= "Verification Code: " . $i['verification_code'] . "\n\n";
        $body .= "Thank you!\n";
        
        return $this->send($toEmail, $subject, $body);
    }
    
    /**
     * Send payment received notification
     */
    public function sendPaymentNotification($invoiceRef, $amount, $toEmail)
    {
        $subject = 'Payment Received';
        $body = "Dear Customer,\n\n";
        $body .= "We have received your payment.\n\n";
        $body .= "Invoice: " . $invoiceRef . "\n";
        $body .= "Amount: " . formatCurrency($amount) . "\n\n";
        $body .= "Thank you!\n";
        
        return $this->send($toEmail, $subject, $body);
    }
    
    /**
     * Send low stock alert
     */
    public function sendLowStockAlert($productName, $quantity, $toEmail)
    {
        $subject = 'Low Stock Alert: ' . $productName;
        $body = "Low Stock Alert\n\n";
        $body .= "Product: " . $productName . "\n";
        $body .= "Current Stock: " . $quantity . "\n\n";
        $body .= "Please reorder soon.\n";
        
        return $this->send($toEmail, $subject, $body);
    }
    
    /**
     * Generic send email
     */
    public function send($to, $subject, $body)
    {
        $headers = "From: " . $this->fromName . " <" . $this->from . ">\r\n";
        $headers .= "Reply-To: " . $this->from . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        return mail($to, $subject, $body, $headers);
    }
}
