<?php
/**
 * PDF Generation for Quotations
 */
class QuotationPDF
{
    private $quotation;
    private $items;
    private $customer;
    
    public function __construct($quotationId)
    {
        $db = Database::getInstance();
        $db->query('SELECT q.*, c.name as customer_name, c.email, c.phone FROM quotations q JOIN customers c ON q.customer_id = c.id WHERE q.id = ?', [$quotationId]);
        $this->quotation = $db->fetch();
        
        if ($this->quotation) {
            $quotation = new Quotation();
            $this->items = $quotation->getItems($quotationId);
        }
    }
    
    public function generate()
    {
        if (!$this->quotation) {
            return false;
        }
        
        ob_start();
        ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quotation <?= $this->quotation['reference'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #667eea; padding-bottom: 20px; margin-bottom: 30px; }
        .company-info h1 { color: #667eea; margin: 0; }
        .qr-code { text-align: right; }
        .qr-code img { width: 150px; height: 150px; }
        .quotation-details { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .detail-box { flex: 1; margin-right: 20px; }
        .detail-box h3 { color: #333; margin-bottom: 10px; }
        .detail-box p { margin: 5px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        table th { background: #667eea; color: white; padding: 12px; text-align: left; }
        table td { padding: 10px; border-bottom: 1px solid #ddd; }
        table tr:nth-child(even) { background: #f9f9f9; }
        .totals { width: 40%; margin-left: auto; margin-top: 20px; }
        .totals table { margin: 0; }
        .totals td { padding: 8px; }
        .total-row { font-weight: bold; background: #f0f0f0; }
        .final-total { font-weight: bold; font-size: 16px; background: #667eea; color: white; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h1><?= APP_NAME ?></h1>
            <p>Professional Business Management System</p>
        </div>
        <div class="qr-code">
            <img src="<?= generateQRCodeURL($this->quotation['verification_code']) ?>" alt="QR Code">
        </div>
    </div>
    
    <div class="quotation-details">
        <div class="detail-box">
            <h3>FROM</h3>
            <p><strong><?= APP_NAME ?></strong></p>
            <p>Business Management System</p>
        </div>
        <div class="detail-box">
            <h3>TO</h3>
            <p><strong><?= Security::escape($this->quotation['customer_name']) ?></strong></p>
            <p><?= Security::escape($this->quotation['email'] ?? '') ?></p>
            <p><?= Security::escape($this->quotation['phone'] ?? '') ?></p>
        </div>
        <div class="detail-box">
            <h3>QUOTATION DETAILS</h3>
            <p><strong>Reference:</strong> <?= Security::escape($this->quotation['reference']) ?></p>
            <p><strong>Date:</strong> <?= formatDate($this->quotation['created_at']) ?></p>
            <p><strong>Expiry Date:</strong> <?= formatDate($this->quotation['expiry_date']) ?></p>
            <p><strong>Status:</strong> <?= ucfirst($this->quotation['status']) ?></p>
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
                <td><?= Security::escape($item['name']) ?></td>
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
                <td>TOTAL AMOUNT</td>
                <td><?= formatCurrency($this->quotation['total_amount']) ?></td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p>Verification Code: <?= Security::escape($this->quotation['verification_code']) ?></p>
        <p>This quotation is valid until <?= formatDate($this->quotation['expiry_date']) ?></p>
        <p><?= APP_NAME ?> - Professional Business Management System</p>
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
        header('Content-Disposition: attachment; filename="quotation_' . $this->quotation['reference'] . '.html"');
        echo $this->generate();
    }
}
