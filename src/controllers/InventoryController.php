<?php
/**
 * Inventory Controller
 * Handles product and stock management
 */
class InventoryController extends BaseController
{
    /**
     * List all products
     */
    public function index()
    {
        $this->authorize(PERM_MANAGE_INVENTORY);
        
        $product = new Product();
        $products = $product->getAll();
        
        return $this->render('inventory.index', [
            'products' => $products,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Create product
     */
    public function create()
    {
        $this->authorize(PERM_MANAGE_INVENTORY);
        
        return $this->render('inventory.create', [
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Store product
     */
    public function store()
    {
        $this->authorize(PERM_MANAGE_INVENTORY);
        $this->validateCsrfToken();
        
        $product = new Product();
        $result = $product->create([
            'sku' => $_POST['sku'],
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? null,
            'price' => $_POST['price'],
            'quantity' => $_POST['quantity'] ?? 0,
            'status' => $_POST['status'] ?? 'active',
        ]);
        
        if (!$result) {
            setFlash('error', 'Failed to create product');
            redirect('/inventory/create');
        }
        
        setFlash('success', 'Product created successfully');
        redirect('/inventory');
    }
    
    /**
     * View stock movements
     */
    public function movements($productId)
    {
        $this->authorize(PERM_MANAGE_INVENTORY);
        
        $this->db->query('SELECT * FROM stock_movements WHERE product_id = ? ORDER BY created_at DESC', [$productId]);
        $movements = $this->db->fetchAll();
        
        return $this->render('inventory.movements', [
            'movements' => $movements,
            'csrfToken' => Security::generateToken(),
        ]);
    }
}
