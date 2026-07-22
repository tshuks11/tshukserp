<?php
/**
 * Settings Controller
 * Manages company settings and branding
 */
class SettingsController extends BaseController
{
    /**
     * Show settings
     */
    public function index()
    {
        $this->authorize(PERM_MANAGE_SETTINGS);
        
        $settings = new CompanySettings();
        $allSettings = $settings->getAll();
        
        return $this->render('settings.index', [
            'settings' => $allSettings,
            'csrfToken' => Security::generateToken(),
        ]);
    }
    
    /**
     * Update settings
     */
    public function update()
    {
        $this->authorize(PERM_MANAGE_SETTINGS);
        $this->validateCsrfToken();
        
        $settings = new CompanySettings();
        
        // Update basic settings
        if (isset($_POST['company_name'])) {
            $settings->set('company_name', Security::sanitize($_POST['company_name']));
        }
        if (isset($_POST['company_email'])) {
            $settings->set('company_email', Security::sanitize($_POST['company_email']));
        }
        if (isset($_POST['company_phone'])) {
            $settings->set('company_phone', Security::sanitize($_POST['company_phone']));
        }
        if (isset($_POST['company_address'])) {
            $settings->set('company_address', Security::sanitize($_POST['company_address']));
        }
        
        // Handle logo upload
        if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['company_logo'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (in_array($ext, ALLOWED_UPLOAD_TYPES)) {
                $filename = 'logo_' . time() . '.' . $ext;
                $path = UPLOADS_PATH . '/' . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $path)) {
                    $settings->set('company_logo', $filename);
                }
            }
        }
        
        setFlash('success', 'Settings updated successfully');
        redirect('/settings');
    }
}
