<?php
/**
 * Expense Controller
 * Handles expense management
 */
class ExpenseController extends BaseController
{
    /**
     * List all expenses
     */
    public function index()
    {
        $this->authorize(PERM_VIEW_REPORTS);
        
        $expense = new Expense();
        $expenses = $expense->getAll();
        
        return $this->render('expenses.index', [
            'expenses' => $expenses,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Create expense
     */
    public function create()
    {
        $this->authorize(PERM_VIEW_REPORTS);
        
        return $this->render('expenses.create', [
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Store expense
     */
    public function store()
    {
        $this->authorize(PERM_VIEW_REPORTS);
        $this->validateCsrfToken();
        
        $expense = new Expense();
        $result = $expense->create([
            'category' => $_POST['category'],
            'description' => $_POST['description'] ?? null,
            'amount' => $_POST['amount'],
            'expense_date' => $_POST['expense_date'],
            'user_id' => $_SESSION['user_id'],
        ]);
        
        if (!$result) {
            setFlash('error', 'Failed to create expense');
            redirect('/expenses/create');
        }
        
        setFlash('success', 'Expense recorded successfully');
        redirect('/expenses');
    }
}
