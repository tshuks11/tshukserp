<?php
/**
 * Report Controller
 * Handles financial and inventory reports
 */
class ReportController extends BaseController
{
    /**
     * Sales report
     */
    public function sales()
    {
        $this->authorize(PERM_VIEW_REPORTS);
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $this->db->query(
            'SELECT DATE(created_at) as date, COUNT(*) as count, SUM(total_amount) as amount FROM invoices WHERE status = "paid" AND created_at BETWEEN ? AND ? GROUP BY DATE(created_at)',
            [$startDate, $endDate]
        );
        $salesData = $this->db->fetchAll();
        
        return $this->render('reports.sales', [
            'salesData' => $salesData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Expense report
     */
    public function expenses()
    {
        $this->authorize(PERM_VIEW_REPORTS);
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $expense = new Expense();
        $expenseData = $expense->getByDateRange($startDate, $endDate);
        $byCategory = $expense->getTotalByCategory($startDate, $endDate);
        
        return $this->render('reports.expenses', [
            'expenseData' => $expenseData,
            'byCategory' => $byCategory,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Export to CSV
     */
    public function exportSalesCSV()
    {
        $this->authorize(PERM_VIEW_REPORTS);
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $this->db->query(
            'SELECT i.reference, i.issue_date, c.name as customer, i.total_amount FROM invoices i JOIN customers c ON i.customer_id = c.id WHERE i.status = "paid" AND i.created_at BETWEEN ? AND ? ORDER BY i.created_at',
            [$startDate, $endDate]
        );
        $data = $this->db->fetchAll();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sales_report_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Invoice', 'Date', 'Customer', 'Amount']);
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
}
