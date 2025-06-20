<?php
/**
 * Générateur de factures PDF pour Zenpark
 */

require_once __DIR__ . '/../../vendor/autoload.php';

class InvoiceGenerator {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    /**
     * Générer une facture PDF
     */
    public function generateInvoice($paymentId, $reservationId) {
        try {
            // Récupération des données
            $paymentData = $this->getPaymentData($paymentId);
            $reservationData = $this->getReservationData($reservationId);
            $userData = $this->getUserData($reservationData['user_id']);
            
            if (!$paymentData || !$reservationData || !$userData) {
                throw new Exception('Données manquantes pour générer la facture');
            }
            
            // Création du PDF
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            
            // Configuration du document
            $pdf->SetCreator('Zenpark');
            $pdf->SetAuthor('Zenpark');
            $pdf->SetTitle('Facture Zenpark - ' . $paymentData['id']);
            $pdf->SetSubject('Facture de stationnement');
            
            // Suppression header/footer par défaut
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // Marges
            $pdf->SetMargins(20, 20, 20);
            $pdf->SetAutoPageBreak(TRUE, 25);
            
            // Ajout d'une page
            $pdf->AddPage();
            
            // Génération du contenu
            $html = $this->generateInvoiceHTML($paymentData, $reservationData, $userData);
            
            // Écriture du HTML dans le PDF
            $pdf->writeHTML($html, true, false, true, false, '');
            
            // Génération du nom de fichier
            $filename = 'facture_zenpark_' . $paymentData['id'] . '_' . date('Y-m-d') . '.pdf';
            
            // Retour du PDF
            return [
                'pdf' => $pdf,
                'filename' => $filename,
                'content' => $pdf->Output($filename, 'S') // S = string
            ];
            
        } catch (Exception $e) {
            error_log("Erreur génération facture: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Générer le HTML de la facture
     */
    private function generateInvoiceHTML($payment, $reservation, $user) {
        $invoiceNumber = 'INV-' . str_pad($payment['id'], 6, '0', STR_PAD_LEFT);
        $date = date('d/m/Y');
        
        return '
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
            .header { text-align: center; margin-bottom: 30px; }
            .summary-row { margin: 10px 0; }
            .total { font-size: 16px; font-weight: bold; padding: 10px; background-color: #007bff; color: white; }
        </style>
        
        <div class="header">
            <h1 style="color: #007bff;">🅿️ ZENPARK</h1>
            <p>Votre solution de stationnement</p>
        </div>
        
        <h2>FACTURE N° ' . $invoiceNumber . '</h2>
        <p>Date: ' . $date . '</p>
        
        <h3>Client:</h3>
        <p>' . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . '<br>
        Email: ' . htmlspecialchars($user['email']) . '</p>
        
        <h3>Détails:</h3>
        <div class="summary-row">Réservation #' . $reservation['id'] . '</div>
        <div class="summary-row">Montant: ' . number_format($payment['amount'], 2, ',', ' ') . ' €</div>
        <div class="summary-row">Statut: PAYÉ ✅</div>
        
        <div class="total">TOTAL: ' . number_format($payment['amount'], 2, ',', ' ') . ' €</div>
        
        <p style="margin-top: 30px; text-align: center;">
            Merci de votre confiance ! 🚗💙
        </p>';
    }
    
    /**
     * Récupérer les données de paiement
     */
    private function getPaymentData($paymentId) {
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE id = ?");
        $stmt->execute([$paymentId]);
        return $stmt->fetch();
    }
    
    /**
     * Récupérer les données de réservation
     */
    private function getReservationData($reservationId) {
        $stmt = $this->pdo->prepare("SELECT * FROM reservations WHERE id = ?");
        $stmt->execute([$reservationId]);
        return $stmt->fetch();
    }
    
    /**
     * Récupérer les données utilisateur
     */
    private function getUserData($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
    
    /**
     * Télécharger la facture
     */
    public function downloadInvoice($paymentId, $reservationId) {
        try {
            $invoice = $this->generateInvoice($paymentId, $reservationId);
            
            // Headers pour le téléchargement
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $invoice['filename'] . '"');
            header('Content-Length: ' . strlen($invoice['content']));
            
            // Sortie du PDF
            echo $invoice['content'];
            exit;
            
        } catch (Exception $e) {
            error_log("Erreur téléchargement facture: " . $e->getMessage());
            header('HTTP/1.1 500 Internal Server Error');
            echo "Erreur lors de la génération de la facture.";
            exit;
        }
    }
}
