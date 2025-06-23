
<?php

/**
 * Contrôleur de gestion des paiements PayPal
 */

// QUICK TEMPORARY FIX - Add this at the very top
require_once __DIR__ . '/../../config/config.php';

require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/Reservation.php';

class PaiementController {
    private $paymentModel;
    private $reservationModel;
    
    // Configuration PayPal (à adapter selon votre environnement)
    private $paypalClientId;
    private $paypalClientSecret;
    private $paypalBaseUrl;
    
    public function __construct() {
        $this->paymentModel = new Payment();
        $this->reservationModel = new Reservation();
        
        // FIXED: Use the constants from your config instead of $_ENV
        $this->paypalClientId = PAYPAL_CLIENT_ID; // Now uses the constant from paypal.php
        $this->paypalClientSecret = PAYPAL_CLIENT_SECRET; // Now uses the constant from paypal.php
        $this->paypalBaseUrl = PAYPAL_API_URL; // Now uses the constant from paypal.php
    }
    
    /**
     * Router principal pour les actions de paiement
     */
    public function handleRequest() {
        $action = $_GET['action'] ?? 'default';
        
        switch ($action) {
            case 'creer_ordre':
                $this->creerOrdrePaypal();
                break;
            case 'capturer':
                $this->capturerPaiement();
                break;
            default:
                $this->afficherPagePaiement();
                break;
        }
    }

    /**
     * Afficher la page de paiement
     */
    public function afficherPagePaiement() {
        $reservationId = $_GET['id'] ?? null;
        $montant = $_GET['montant'] ?? null;
        
        if (!$reservationId || !$montant) {
            header('Location: /?page=mes_reservations&error=missing_params');
            exit;
        }
        
        // CORRECT
include __DIR__ . '/../views/pages/paiement.php';
    }
    
    /**
     * Créer un ordre PayPal
     */
    public function creerOrdrePaypal() {
        header('Content-Type: application/json');
        
        try {
            // Validation de la requête
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Méthode non autorisée');
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['reservation_id']) || !isset($input['amount'])) {
                throw new Exception('Données manquantes');
            }
            
            $reservationId = (int)$input['reservation_id'];
            $amount = (float)$input['amount'];
            
            // Vérification de la réservation
            $reservation = $this->reservationModel->getReservationById($reservationId);
            if (!$reservation) {
                throw new Exception('Réservation introuvable');
            }
            
            // Validation du montant
            if ($amount <= 0) {
                throw new Exception('Montant invalide');
            }
            
            // Création de l'ordre PayPal
            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => 'RESERVATION_' . $reservationId,
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => number_format($amount, 2, '.', '')
                    ],
                    'description' => "Réservation parking - Place {$reservation['numero_place']}"
                ]],
                'application_context' => [
                    'return_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/?page=valider_paiement&success=1',
                    'cancel_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/?page=paiement&cancelled=1'
                ]
            ];
            
            $paypalOrder = $this->callPayPalAPI('/v2/checkout/orders', $orderData);
            
            if (!$paypalOrder || !isset($paypalOrder['id'])) {
                throw new Exception('Erreur lors de la création de l\'ordre PayPal');
            }
            
            // Enregistrement du paiement en base
            $this->paymentModel->createPayment([
    'reservation_id' => $reservationId,
    'paypal_order_id' => $paypalOrder['id'],
    'amount' => $amount,
    'currency' => 'EUR',
    'status' => 'pending'
]);
            echo json_encode([
                'success' => true,
                'order_id' => $paypalOrder['id']
            ]);
            
        } catch (Exception $e) {
            error_log("Erreur création ordre PayPal: " . $e->getMessage());
            echo json_encode([
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Capturer le paiement PayPal
     */
    public function capturerPaiement() {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Méthode non autorisée');
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['order_id']) || !isset($input['reservation_id'])) {
                throw new Exception('Données manquantes');
            }
            
            $orderId = $input['order_id'];
            $reservationId = (int)$input['reservation_id'];
            
            // Capture du paiement via PayPal
            $captureResult = $this->callPayPalAPI("/v2/checkout/orders/{$orderId}/capture", new stdClass());

            
            if (!$captureResult || $captureResult['status'] !== 'COMPLETED') {
                throw new Exception('Échec de la capture du paiement');
            }
            
            // Extraction de l'ID de paiement
            $paymentId = $captureResult['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;
            
            // Mise à jour du statut du paiement
            $this->paymentModel->updatePaymentStatus($orderId, 'completed', $paymentId);
            
            // Marquage de la réservation comme payée
            $this->paymentModel->markReservationAsPaid($reservationId);
            
            echo json_encode([
                'success' => true,
                'message' => 'Paiement validé avec succès',
                'payment_id' => $paymentId
            ]);
            
        } catch (Exception $e) {
            error_log("Erreur capture paiement: " . $e->getMessage());
            echo json_encode([
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Appel API PayPal
     */
    private function callPayPalAPI($endpoint, $data = null) {
        try {
            // Récupération du token d'accès
            $accessToken = $this->getPayPalAccessToken();
            
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => $this->paypalBaseUrl . $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $accessToken
                ],
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true
            ]);
            
            if ($data !== null) {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            if (curl_error($curl)) {
                throw new Exception('Erreur cURL: ' . curl_error($curl));
            }
            
            curl_close($curl);
            
            if ($httpCode >= 400) {
                error_log("Erreur API PayPal {$httpCode}: " . $response);
                throw new Exception('Erreur API PayPal');
            }
            
            return json_decode($response, true);
            
        } catch (Exception $e) {
            error_log("Erreur appel PayPal API: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Récupération du token d'accès PayPal
     */
    private function getPayPalAccessToken() {
        try {
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => $this->paypalBaseUrl . '/v1/oauth2/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
                CURLOPT_USERPWD => $this->paypalClientId . ':' . $this->paypalClientSecret,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Accept-Language: en_US'
                ],
                CURLOPT_TIMEOUT => 30
            ]);
            
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            if (curl_error($curl)) {
                throw new Exception('Erreur cURL token: ' . curl_error($curl));
            }
            
            curl_close($curl);
            
            if ($httpCode !== 200) {
                throw new Exception('Impossible d\'obtenir le token PayPal');
            }
            
            $result = json_decode($response, true);
            
            if (!isset($result['access_token'])) {
                throw new Exception('Token PayPal non reçu');
            }
            
            return $result['access_token'];
            
        } catch (Exception $e) {
            error_log("Erreur token PayPal: " . $e->getMessage());
            throw new Exception('Erreur d\'authentification PayPal');
        }
    }
    
    /**
     * Vérifier le statut d'un paiement
     */
    public function verifierStatutPaiement($reservationId) {
        try {
            return $this->paymentModel->hasValidPayment($reservationId);
        } catch (Exception $e) {
            error_log("Erreur vérification paiement: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Afficher la page de paiement (si nécessaire)
     */
    public function payer($reservationId) {
        try {
            // Vérification de la réservation
            $reservation = $this->reservationModel->getReservationById($reservationId);
            if (!$reservation) {
                header('Location: /?page=mes_reservations&error=reservation_not_found');
                exit;
            }
            
            // Vérifier que la réservation appartient à l'utilisateur connecté
            if ($reservation['user_id'] !== $_SESSION['user']['id']) {
                header('Location: /?page=mes_reservations&error=unauthorized');
                exit;
            }
            
            // Vérifier si déjà payé
            if ($this->verifierStatutPaiement($reservationId)) {
                header('Location: /?page=mes_reservations&info=already_paid');
                exit;
            }
            
            // Calcul du montant (à adapter selon votre logique)
            // Exemple simple - vous devriez avoir une logique plus complexe
            $montant = 10.00; // Montant par défaut ou calculé
            
            // Redirection vers la page de paiement avec les paramètres
            header("Location: /?page=paiement&id={$reservationId}&montant={$montant}");
            exit;
            
        } catch (Exception $e) {
            error_log("Erreur affichage paiement: " . $e->getMessage());
            header('Location: /?page=mes_reservations&error=payment_error');
            exit;
        }
    }
}