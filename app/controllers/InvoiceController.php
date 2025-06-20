<?php
require_once __DIR__ . '/../models/InvoiceGenerator.php';

class InvoiceController {
    private $invoiceGenerator;
    
    public function __construct() {
        $this->invoiceGenerator = new InvoiceGenerator();
    }
    
    public function downloadInvoice() {
        $paymentId = $_GET['payment_id'] ?? null;
        $reservationId = $_GET['reservation_id'] ?? null;
        
        if (!$paymentId || !$reservationId) {
            header('HTTP/1.1 400 Bad Request');
            echo "Paramètres manquants";
            exit;
        }
        
        $this->invoiceGenerator->downloadInvoice($paymentId, $reservationId);
    }
}
