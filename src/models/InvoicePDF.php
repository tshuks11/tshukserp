<?php
/**
 * PDF Generation for Invoices
 */
class InvoicePDF
{
    private $invoice;
    private $items;
    private $customer;
    
    public function __construct($invoiceId)
    {
        $db = Database::getInstance();
        $db->query('SELECT i.*, c.name as customer_name, c.email, c.phone, c.address FROM invoices i JOIN customers c ON i.customer_id = c.id WHERE i.id = ?', [$invoiceId]);
        $this->invoice = $db->fetch();
        
        if ($this->invoice) {
            $invoice = new Invoice();
            $this->items = $invoice->getItems($invoiceId);
        }
    }
    
    public function generate()
    {
        if (!$this->invoice) {
            return false;
        }
        
        ob_start();
        ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice <?= $this->invoice['reference'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #667eea; padding-bottom: 20px; margin-bottom: 30px; }
        .company-info h1 { color: #667eea; margin: 0; font-size: 28px; }
        .qr-code { text-align: right; }
        .qr-code img { width: 150px; height: 150px; }
        .invoice-details { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .detail-box { flex: 1; margin-right: 20px; }
        .detail-box h3 { color: #333; margin-bottom: 10px; font-size: 12px; text-transform: uppercase; }
        .detail-box p { margin: 5px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        table th { background: #667eea; color: white; padding: 12px; text-align: left; }
        table td { padding: 12px; border-bottom: 1px solid #ddd; }
        table tr:nth-child(even) { background: #f9f9f9; }
        .totals { width: 50%; margin-left: auto; margin-top: 20px; }
        .totals table { margin: 0; }
        .totals tr { border: none; }
        .totals td { padding: 8px 12px; border-bottom: 1px solid #ddd; }
        .totals td:first-child { text-align: right; }
        .final-total { font-weight: bold; font-size: 18px; background: #667eea; color: white; }
        .status-box { margin: 20px 0; padding: 15px; background: #e8f5e9; border-left: 4px solid #4caf50; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h1>INVOICE</h1>
            <p><?= APP_NAME ?></p>
        </div>
        <div class="qr-code">
            <img src="<?= generateQRCodeURL($this->invoice['verification_code']) ?>" alt="QR Code">
        </div>
    </div>
    
    <div class="invoice-details">
        <div class="detail-box">
            <h3>Bill To</h3>
            <p><strong><?= Security::escape($this->invoice['customer_name']) ?></strong></p>
            <p><?= Security::escape($this->invoice['email'] ?? '') ?></p>
            <p><?= Security::escape($this->invoice['phone'] ?? '') ?></p>
            <p><?= Security::escape($this->invoice['address'] ?? '') ?></p>
        </div>
        <div class="detail-box">
            <h3>Invoice Details</h3>
            <p><strong>Invoice #:</strong> <?= Security::escape($this->invoice['reference']) ?></p>
            <p><strong>Status:</strong> <?= ucfirst(str_replace('_', ' ', $this->invoice['status'])) ?></p>
            <p><strong>Issue Date:</strong> <?= formatDate($this->invoice['issue_date']) ?></p>
            <p><strong>Due Date:</strong> <?= formatDate($this->invoice['due_date']) ?></p>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->items as $item): ?>
            <tr>
                <td><?= Security::escape($item['product_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= formatCurrency($item['unit_price']) ?></td>
                <td><?= formatCurrency($item['line_total']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="totals">
        <table>
            <tr class="final-total">
                <td>TOTAL</td>
                <td><?= formatCurrency($this->invoice['total_amount']) ?></td>
            </tr>
        </table>
    </div>
    
    <div class="status-box">
        <strong>Status:</strong> <?= ucfirst(str_replace('_', ' ', $this->invoice['status'])) ?><br>
        <strong>Verification Code:</strong> <?= Security::escape($this->invoice['verification_code']) ?>
    </div>
    
    <div class="footer">
        <p><?= APP_NAME ?> - Professional Business Management System</p>
        <p>This invoice is valid and can be verified using the QR code above</p>
    </div>
</body>
</html>
        <?php
        $html = ob_get_clean();
        return $html;
    }
    
    public function download()
    {
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="invoice_' . $this->invoice['reference'] . '.html"');
        echo $this->generate();
    }
}
