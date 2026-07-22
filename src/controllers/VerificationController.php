<?php
/**
 * Document Verification Controller
 * Handles public document verification
 */
class VerificationController extends BaseController
{
    /**
     * Verify document
     */
    public function verify()
    {
        $code = $_GET['code'] ?? '';
        
        if (empty($code)) {
            return $this->render('verification.index', ['document' => null]);
        }
        
        $verification = new DocumentVerification();
        $document = $verification->verifyByCode($code);
        
        if ($document) {
            $details = $verification->getDetails($document['type'], $document['id']);
            return $this->render('verification.result', [
                'document' => $details,
                'type' => $document['type'],
                'verified' => true,
            ]);
        }
        
        return $this->render('verification.result', [
            'verified' => false,
        ]);
    }
}
