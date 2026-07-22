<?php
/**
 * Quotation Controller
 * Handles quotation management
 */
class QuotationController extends BaseController
{
    /**
     * List all quotations
     */
    public function index()
    {
        $this->authorize(PERM_CREATE_QUOTATION);
        
        $quotation = new Quotation();
        $quotations = $quotation->getAll();
        
        return $this->render('quotations.index', [
            'quotations' => $quotations,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Create new quotation
     */
    public function create()
    {
        $this->authorize(PERM_CREATE_QUOTATION);
        
        $customer = new Customer();
        $product = new Product();
        
        return $this->render('quotations.create', [
            'customers' => $customer->getAll(),
            'products' => $product->getAll(),
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Store quotation
     */
    public function store()
    {
        $this->authorize(PERM_CREATE_QUOTATION);
        $this->validateCsrfToken();
        
        $quotation = new Quotation();
        
        // Create quotation
        $result = $quotation->create([
            'customer_id' => $_POST['customer_id'],
            'user_id' => $_SESSION['user_id'],
            'total_amount' => 0,
            'expiry_date' => $_POST['expiry_date'],
        ]);
        
        if (!$result) {
            setFlash('error', 'Failed to create quotation');
            redirect('/quotations/create');
        }
        
        // Add items
        $totalAmount = 0;
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            foreach ($_POST['items'] as $item) {
                if (empty($item['product_id']) || empty($item['quantity']) || empty($item['unit_price'])) {
                    continue;
                }
                
                $quotation->addItem(
                    $result['id'],
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price']
                );
                
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }
        }
        
        // Update total amount
        $this->db->query('UPDATE quotations SET total_amount = ? WHERE id = ?', [$totalAmount, $result['id']]);
        
        setFlash('success', 'Quotation created successfully');
        redirect('/quotations/' . $result['id']);
    }
    
    /**
     * View quotation
     */
    public function view($id)
    {
        $this->authorize(PERM_CREATE_QUOTATION);
        
        $quotation = new Quotation();
        $q = $quotation->getById($id);
        
        if (!$q) {
            http_response_code(404);
            die('Quotation not found');
        }
        
        return $this->render('quotations.view', [
            'quotation' => $q,
            'items' => $quotation->getItems($id),
            'qrCode' => generateQRCodeURL($q['verification_code']),
            'csrfToken' => Security::generateToken(),
        ]);
    }
}
