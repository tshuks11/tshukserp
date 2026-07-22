<?php
/**
 * Analytics Controller
 * Provides insights and reports
 */
class AnalyticsController extends BaseController
{
    /**
     * Customer analytics
     */
    public function customers()
    {
        $this->authorize(PERM_VIEW_REPORTS);
        
        $this->db->query(
            'SELECT c.id, c.name, COUNT(i.id) as invoice_count, SUM(i.total_amount) as total_spent FROM customers c LEFT JOIN invoices i ON c.id = i.customer_id GROUP BY c.id ORDER BY total_spent DESC LIMIT 20'
        );
        $customers = $this->db->fetchAll();
        
        return $this->render('analytics.customers', [
            'customers' => $customers,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Inventory analytics
     */
    public function inventory()
    {
        $this->authorize(PERM_VIEW_REPORTS);
        
        $this->db->query('SELECT * FROM products WHERE status = "active" ORDER BY quantity ASC');
        $products = $this->db->fetchAll();
        
        $lowStock = array_filter($products, function($p) {
            return $p['quantity'] < 10;
        });
        
        return $this->render('analytics.inventory', [
            'products' => $products,
            'lowStock' => $lowStock,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Sales by product
     */
    public function salesByProduct()
    {
        $this->authorize(PERM_VIEW_REPORTS);
        
        $this->db->query(
            'SELECT p.id, p.name, p.sku, SUM(ii.quantity) as total_qty, SUM(ii.line_total) as total_amount FROM products p LEFT JOIN invoice_items ii ON p.id = ii.product_id LEFT JOIN invoices i ON ii.invoice_id = i.id WHERE i.status = "paid" GROUP BY p.id ORDER BY total_amount DESC'
        );
        $salesData = $this->db->fetchAll();
        
        return $this->render('analytics.sales_by_product', [
            'salesData' => $salesData,
            'csrfToken' => Security::generateToken(),
        ]);
    }
}
