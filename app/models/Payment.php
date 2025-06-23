<?php
/**
 * Modèle Payment - Gestion des paiements PayPal
 */
class Payment {
    private $db;
    
    public function __construct() {
        // Utiliser la connexion PDO globale définie dans config.php
        global $pdo;
        
        if (!$pdo) {
            require_once __DIR__ . '/../../config/config.php';
            global $pdo;
        }
        
        if (!$pdo) {
            throw new Exception("Connexion à la base de données non disponible");
        }
        
        $this->db = $pdo;
    }
    
    /**
     * Créer un enregistrement de paiement
     */
    public function createPayment($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO payments (
                    reservation_id, 
                    paypal_order_id, 
                    paypal_payment_id,
                    amount, 
                    currency,
                    status, 
                    payment_method,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            return $stmt->execute([
                $data['reservation_id'],
                $data['paypal_order_id'] ?? null,
                $data['paypal_payment_id'] ?? null,
                $data['amount'],
                $data['currency'] ?? 'EUR',
                $data['status'] ?? 'pending',
                $data['payment_method'] ?? 'paypal'
            ]);
            
        } catch (PDOException $e) {
            error_log("Erreur création paiement: " . $e->getMessage());
            throw new Exception("Erreur lors de la création du paiement");
        }
    }
    
    /**
     * Mettre à jour le statut d'un paiement
     */
    public function updatePaymentStatus($paypalOrderId, $status, $paypalPaymentId = null) {
        try {
            $sql = "UPDATE payments SET status = ?, updated_at = NOW()";
            $params = [$status];
            
            if ($paypalPaymentId) {
                $sql .= ", paypal_payment_id = ?";
                $params[] = $paypalPaymentId;
            }
            
            $sql .= " WHERE paypal_order_id = ?";
            $params[] = $paypalOrderId;
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            error_log("Erreur mise à jour paiement: " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour du paiement");
        }
    }
    
    /**
     * Récupérer un paiement par order_id PayPal
     */
    public function getPaymentByOrderId($paypalOrderId) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM payments 
                WHERE paypal_order_id = ?
            ");
            $stmt->execute([$paypalOrderId]);
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Erreur récupération paiement: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupérer les paiements d'une réservation
     */
    public function getPaymentsByReservation($reservationId) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM payments 
                WHERE reservation_id = ?
                ORDER BY created_at DESC
            ");
            $stmt->execute([$reservationId]);
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Erreur récupération paiements réservation: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Vérifier si une réservation a un paiement validé
     */
    public function hasValidPayment($reservationId) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM payments 
                WHERE reservation_id = ? AND status IN ('completed', 'approved')
            ");
            $stmt->execute([$reservationId]);
            
            $result = $stmt->fetch();
            return $result['count'] > 0;
            
        } catch (PDOException $e) {
            error_log("Erreur vérification paiement: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Marquer une réservation comme payée
     */
    public function markReservationAsPaid($reservationId) {
        try {
            $stmt = $this->db->prepare("
                UPDATE reservations 
                SET payment_status = 'paid', updated_at = NOW() 
                WHERE id = ?
            ");
            
            return $stmt->execute([$reservationId]);
            
        } catch (PDOException $e) {
            error_log("Erreur marquage réservation payée: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupérer les statistiques de paiement
     */
    public function getPaymentStats($dateStart = null, $dateEnd = null) {
        try {
            $sql = "
                SELECT 
                    COUNT(*) as total_payments,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_payments,
                    SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_amount,
                    AVG(CASE WHEN status = 'completed' THEN amount ELSE NULL END) as average_amount
                FROM payments
            ";
            
            $params = [];
            
            if ($dateStart && $dateEnd) {
                $sql .= " WHERE created_at BETWEEN ? AND ?";
                $params = [$dateStart, $dateEnd];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Erreur stats paiements: " . $e->getMessage());
            return [];
        }
    }
}