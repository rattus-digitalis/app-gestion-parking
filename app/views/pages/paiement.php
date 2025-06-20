<?php
// ACTIVATION DU DÉBOGAGE - À RETIRER EN PRODUCTION
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../debug.log');

// Test de base
echo "<!-- Debug: Script démarré -->\n";

try {
    // Inclusion des templates de base
    require_once __DIR__ . '/../templates/head.php';
    echo "<!-- Debug: head.php inclus -->\n";
    
    require_once __DIR__ . '/../templates/nav.php';
    echo "<!-- Debug: nav.php inclus -->\n";
    
    // Inclusion des modèles
    require_once __DIR__ . '/../../models/Reservation.php';
    echo "<!-- Debug: Reservation.php inclus -->\n";
    
    require_once __DIR__ . '/../../models/Car.php';
    echo "<!-- Debug: Car.php inclus -->\n";

    // Récupération et validation des paramètres
    $reservationId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $montant = filter_input(INPUT_GET, 'montant', FILTER_VALIDATE_FLOAT);

    echo "<!-- Debug: reservationId = " . var_export($reservationId, true) . " -->\n";
    echo "<!-- Debug: montant = " . var_export($montant, true) . " -->\n";

    // Validation des paramètres requis
    if (!$reservationId || !$montant || $montant <= 0) {
        throw new Exception("Paramètres invalides - ID: {$reservationId}, Montant: {$montant}");
    }

    // Récupération des données de réservation
    $reservationModel = new Reservation();
    echo "<!-- Debug: Modèle Reservation instancié -->\n";
    
    $reservation = $reservationModel->getReservationById($reservationId);
    echo "<!-- Debug: Réservation récupérée = " . var_export($reservation, true) . " -->\n";

    if (!$reservation) {
        throw new Exception("Réservation introuvable avec l'ID: {$reservationId}");
    }

    // Vérification de la propriété de la réservation
    if (!isset($_SESSION['user']['id']) || $reservation['user_id'] != $_SESSION['user']['id']) {
        throw new Exception("Accès non autorisé à cette réservation");
    }

    // Récupération des informations du véhicule
    $carModel = new Car();
    $vehicule = null;
    if (!empty($reservation['car_id'])) {
        $vehicule = $carModel->getById((int)$reservation['car_id']);
        echo "<!-- Debug: Véhicule récupéré = " . var_export($vehicule, true) . " -->\n";
    }

    // Formatage sécurisé du montant
    $prix = number_format($montant, 2, ',', ' ');
    $prixJs = number_format($montant, 2, '.', '');
    
    echo "<!-- Debug: Formatage terminé - prix = {$prix}, prixJs = {$prixJs} -->\n";

} catch (Exception $e) {
    echo "<div class='container mt-4'>";
    echo "<div class='alert alert-danger' role='alert'>";
    echo "<h4 class='alert-heading'>Erreur de débogage</h4>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Fichier:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Ligne:</strong> " . $e->getLine() . "</p>";
    echo "<pre><strong>Stack trace:</strong>\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "<a href='/?page=mes_reservations' class='btn btn-primary'>Retour aux réservations</a>";
    echo "</div></div>";
    
    if (file_exists(__DIR__ . '/../templates/footer.php')) {
        require_once __DIR__ . '/../templates/footer.php';
    }
    exit;
}
?>

<main class="container payment-page mt-4" role="main">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="text-center mb-4">
                <i class="fas fa-credit-card"></i> Paiement sécurisé
            </h1>

            <!-- Résumé de la réservation -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title mb-0"><i class="fas fa-clipboard-list"></i> Résumé de votre réservation</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>Place :</strong> <?= htmlspecialchars($reservation['numero_place'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></p>
                            <p><strong>Étage :</strong> <?= htmlspecialchars($reservation['etage'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong>Début :</strong> <?= isset($reservation['date_start']) ? date('d/m/Y à H:i', strtotime($reservation['date_start'])) : 'N/A' ?></p>
                            <p><strong>Fin :</strong> <?= isset($reservation['date_end']) ? date('d/m/Y à H:i', strtotime($reservation['date_end'])) : 'N/A' ?></p>
                        </div>
                    </div>
                    <p><strong>Type de véhicule :</strong> <?= htmlspecialchars($vehicule['type'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') ?></p>
                    <hr>
                    <div class="text-center">
                        <h3 class="text-success">
                            <strong>Montant à régler : <?= $prix ?> €</strong>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Zone de paiement -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0"><i class="fab fa-paypal"></i> Paiement PayPal</h3>
                </div>
                <div class="card-body">
                    <div id="result-message" class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> Cliquez sur le bouton PayPal pour procéder au paiement sécurisé.
                    </div>
                    
                    <div id="paypal-button-container" class="text-center"></div>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt"></i> Paiement 100% sécurisé avec PayPal
                        </small>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="text-center mt-4">
                <a href="/?page=mes_reservations" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour aux réservations
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Section de débogage JavaScript -->
<div class="container mt-4" style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
    <h5>Informations de débogage JavaScript :</h5>
    <div id="js-debug"></div>
</div>

<!-- SDK PayPal -->
<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=EUR&components=buttons&enable-funding=paypal,card"></script>

<script>
    // Debug JavaScript
    const debugElement = document.getElementById('js-debug');
    function logDebug(message) {
        console.log(message);
        if (debugElement) {
            debugElement.innerHTML += '<p>' + new Date().toISOString() + ': ' + message + '</p>';
        }
    }

    // Variables globales sécurisées
    const RESERVATION_ID = <?= json_encode($reservationId, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const MONTANT = <?= json_encode($prixJs, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    logDebug('Variables initialisées - ID: ' + RESERVATION_ID + ', Montant: ' + MONTANT);

    // Fonction utilitaire pour afficher les messages
    function showMessage(message, type = 'info') {
        const resultMessage = document.getElementById('result-message');
        if (!resultMessage) {
            logDebug('Erreur: élément result-message non trouvé');
            return;
        }
        
        const iconMap = {
            'info': 'fas fa-info-circle',
            'success': 'fas fa-check-circle', 
            'warning': 'fas fa-exclamation-triangle',
            'error': 'fas fa-times-circle'
        };
        
        const alertClass = {
            'info': 'alert-info',
            'success': 'alert-success',
            'warning': 'alert-warning', 
            'error': 'alert-danger'
        };
        
        resultMessage.className = `alert ${alertClass[type] || 'alert-info'}`;
        resultMessage.innerHTML = `<i class="${iconMap[type] || iconMap.info}"></i> ${message}`;
        resultMessage.setAttribute('role', 'alert');
        
        logDebug('Message affiché: ' + message + ' (type: ' + type + ')');
    }

    // Fonction pour effectuer des requêtes AJAX sécurisées
    async function makeSecureRequest(url, data) {
        try {
            logDebug('Début requête AJAX vers: ' + url);
            logDebug('Données envoyées: ' + JSON.stringify(data));
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data),
                credentials: 'same-origin'
            });

            logDebug('Réponse reçue - Status: ' + response.status + ' ' + response.statusText);

            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status} ${response.statusText}`);
            }

            const result = await response.json();
            logDebug('Réponse JSON: ' + JSON.stringify(result));
            
            if (result.error) {
                throw new Error(result.error);
            }

            return result;
            
        } catch (error) {
            logDebug('Erreur de requête: ' + error.message);
            throw error;
        }
    }

    // Configuration et initialisation des boutons PayPal
    document.addEventListener('DOMContentLoaded', function() {
        logDebug('DOM chargé, initialisation PayPal...');
        
        // Vérification de la disponibilité de PayPal
        if (typeof paypal === 'undefined') {
            showMessage('❌ Erreur de chargement PayPal. Veuillez actualiser la page.', 'error');
            logDebug('Erreur: PayPal SDK non chargé');
            return;
        }

        logDebug('PayPal SDK disponible, configuration des boutons...');

        paypal.Buttons({
            locale: 'fr_FR',
            
            style: {
                color: 'blue',
                shape: 'rect',
                label: 'pay',
                height: 50,
                layout: 'vertical'
            },

            // Création de l'ordre PayPal
            createOrder: async function(data, actions) {
                showMessage('💳 Création de votre commande PayPal...', 'info');
                
                try {
                    const result = await makeSecureRequest('/?action=creer_ordre_paypal', {
                        reservation_id: RESERVATION_ID,
                        amount: MONTANT
                    });

                    showMessage('✅ Redirection vers PayPal...', 'success');
                    return result.order_id;
                    
                } catch (error) {
                    showMessage(`❌ Erreur lors de la création de la commande: ${error.message}`, 'error');
                    throw error;
                }
            },

            // Validation du paiement
            onApprove: async function(data, actions) {
                showMessage('🔄 Validation du paiement en cours...', 'info');
                
                try {
                    const result = await makeSecureRequest('/?action=capturer_paiement', {
                        order_id: data.orderID,
                        reservation_id: RESERVATION_ID
                    });

                    showMessage('🎉 Paiement réussi ! Redirection vers vos réservations...', 'success');
                    
                    // Redirection avec délai pour laisser le temps de lire le message
                    setTimeout(function() {
                        window.location.href = '/?page=mes_reservations&success=payment_completed';
                    }, 2500);
                    
                } catch (error) {
                    showMessage(`❌ Erreur lors de la validation du paiement: ${error.message}`, 'error');
                }
            },

            // Annulation du paiement
            onCancel: function(data) {
                showMessage('⚠️ Paiement annulé. Vous pouvez réessayer quand vous le souhaitez.', 'warning');
                logDebug('Paiement annulé par l\'utilisateur: ' + JSON.stringify(data));
            },

            // Gestion des erreurs PayPal
            onError: function(err) {
                showMessage('❌ Une erreur PayPal est survenue. Veuillez réessayer ou contacter le support.', 'error');
                logDebug('Erreur PayPal: ' + JSON.stringify(err));
            }

        }).render('#paypal-button-container').catch(function(err) {
            showMessage('❌ Impossible de charger le module de paiement PayPal.', 'error');
            logDebug('Erreur lors du rendu PayPal: ' + JSON.stringify(err));
        });
        
        logDebug('Configuration PayPal terminée');
    });
</script>

<?php 
try {
    require_once __DIR__ . '/../templates/footer.php';
    echo "<!-- Debug: footer.php inclus -->\n";
} catch (Exception $e) {
    echo "<!-- Debug: Erreur footer.php - " . $e->getMessage() . " -->\n";
}
?>