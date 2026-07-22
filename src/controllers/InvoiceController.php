<?php
/**
 * Invoice Controller
 * Handles invoice management
 */
class InvoiceController extends BaseController
{
    /**
     * List all invoices
     */
    public function index()
    {
        $this->authorize(PERM_CREATE_INVOICE);
        
        $invoice = new Invoice();
        $invoices = $invoice->getAll();
        
        return $this->render('invoices.index', [
            'invoices' => $invoices,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Create invoice from quotation
     */
    public function fromQuotation()
    {
        $this->authorize(PERM_CREATE_INVOICE);
        
        $quotation = new Quotation();
        $quotations = $quotation->getAll();
        
        return $this->render('invoices.from_quotation', [
            'quotations' => $quotations,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Store invoice from quotation
     */
    public function storeFromQuotation()
    {
        $this->authorize(PERM_CREATE_INVOICE);
        $this->validateCsrfToken();
        
        $invoice = new Invoice();
        $result = $invoice->createFromQuotation($_POST['quotation_id'], $_SESSION['user_id']);
        
        if (!$result) {
            setFlash('error', 'Failed to create invoice');
            redirect('/invoices/from-quotation');
        }
        
        setFlash('success', 'Invoice created successfully');
        redirect('/invoices/' . $result['id']);
    }
    
    /**
     * View invoice
     */
    public function view($id)
    {
        $this->authorize(PERM_CREATE_INVOICE);
        
        $invoice = new Invoice();
        $inv = $invoice->getById($id);
        
        if (!$inv) {
            http_response_code(404);
            die('Invoice not found');
        }
        
        return $this->render('invoices.view', [
            'invoice' => $inv,
            'items' => $invoice->getItems($id),
            'qrCode' => generateQRCodeURL($inv['verification_code']),
            'csrfToken' => Security::generateToken(),
        ]);
    }
}
