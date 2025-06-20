<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Zenpark</title>
    <script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENT_ID ?>&currency=EUR"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .container { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .summary { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .row { display: flex; justify-content: space-between; margin: 10px 0; }
        .total { border-top: 2px solid #ddd; padding-top: 15px; font-weight: bold; font-size: 1.2em; }
        .btn { background: #007bff; color: white; padding: 15px 30px; border: none; border-radius: 8px; width: 100%; font-size: 16px; cursor: pointer; margin: 10px 0; }
        .btn:hover { background: #0056b3; }
        .notification { padding: 15px; border-radius: 8px; margin: 10px 0; display: none; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        #paypal-button-container { margin: 20px 0; min-height: 60px; }
        .payment-mode { margin: 20px 0; }
        .mode-btn { padding: 10px 20px; margin: 5px; border: 2px solid #007bff; background: white; color: #007bff; border-radius: 5px; cursor: pointer; }
        .mode-btn.active { background: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>💳 Paiement Zenpark</h1>
        
        <div id="notification" class="notification"></div>
        
        <div class="summary">
            <h3>📋 Résumé</h3>
            <div class="row">
                <span>Réservation:</span>
                <span>#<?= htmlspecialchars($_GET['id'] ?? 'N/A') ?></span>
            </div>
            <div class="row">
                <span>Place:</span>
                <span>Premium</span>
            </div>
            <div class="row total">
                <span>Total:</span>
                <span><?= number_format((float)($_GET['montant'] ?? 0), 2, ',', ' ') ?> €</span>
            </div>
        </div>
        
        <div class="payment-mode">
            <h3>Choisissez votre mode de test :</h3>
            <button class="mode-btn active" onclick="setMode('real')">🔄 PayPal Réel</button>
            <button class="mode-btn" onclick="setMode('simulation')">⚡ Simulation Rapide</button>
        </div>
        
        <!-- Bouton PayPal officiel -->
        <div id="paypal-button-container"></div>
        
        <!-- Bouton de simulation -->
        <button id="simulate-btn" class="btn" style="display: none;">
            ⚡ Simuler le paiement (Test)
        </button>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="/?page=mes_reservations">← Retour</a>
        </p>
    </div>

    <script>
        const reservationId = <?= json_encode($_GET['id'] ?? null) ?>;
        const montant = <?= json_encode((float)($_GET['montant'] ?? 0)) ?>;
        let currentMode = 'real';
        
        function showNotification(message, type) {
            const notif = document.getElementById('notification');
            notif.textContent = message;
            notif.className = `notification ${type}`;
            notif.style.display = 'block';
            
            if (type !== 'error') {
                setTimeout(() => notif.style.display = 'none', 5000);
            }
        }
        
        function setMode(mode) {
            currentMode = mode;
            document.querySelectorAll('.mode-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            if (mode === 'real') {
                document.getElementById('paypal-button-container').style.display = 'block';
                document.getElementById('simulate-btn').style.display = 'none';
            } else {
                document.getElementById('paypal-button-container').style.display = 'none';
                document.getElementById('simulate-btn').style.display = 'block';
            }
        }
        
        // Validation
        if (!reservationId || !montant || montant <= 0) {
            showNotification('❌ Paramètres invalides', 'error');
        } else {
            // Configuration PayPal OFFICIELLE
            paypal.Buttons({
                createOrder: function(data, actions) {
                    showNotification('🔄 Création de la commande PayPal...', 'info');
                    
                    return fetch('/?page=paiement&action=creer_ordre', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            reservation_id: reservationId,
                            amount: montant
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            showNotification('❌ ' + data.error, 'error');
                            throw new Error(data.error);
                        }
                        showNotification('✅ Commande PayPal créée!', 'success');
                        return data.order_id;
                    })
                    .catch(error => {
                        showNotification('❌ Erreur: ' + error.message, 'error');
                        throw error;
                    });
                },
                
                onApprove: function(data, actions) {
                    showNotification('🔄 Validation du paiement...', 'info');
                    
                    return fetch('/?page=paiement&action=capturer', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            order_id: data.orderID,
                            reservation_id: reservationId
                        })
                    })
                    .then(response => response.json())
                    .then(captureData => {
                        if (captureData.error) {
                            showNotification('❌ ' + captureData.error, 'error');
                        } else {
                            showNotification('🎉 Paiement confirmé! Redirection...', 'success');
                            setTimeout(() => {
                                window.location.href = '/?page=valider_paiement&success=1&payment_id=' + captureData.payment_id + '&reservation_id=' + reservationId;
                            }, 2000);
                        }
                    });
                },
                
                onError: function(err) {
                    showNotification('❌ Erreur PayPal: ' + err, 'error');
                },
                
                onCancel: function(data) {
                    showNotification('⚠️ Paiement annulé', 'info');
                }
            }).render('#paypal-button-container');
        }
        
        // Bouton de simulation
        document.getElementById('simulate-btn').addEventListener('click', function() {
            showNotification('⚡ Simulation du paiement...', 'info');
            
            // Étape 1: Créer l'ordre
            fetch('/?page=paiement&action=creer_ordre', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    reservation_id: reservationId,
                    amount: montant
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showNotification('❌ ' + data.error, 'error');
                } else {
                    showNotification('✅ Ordre créé! Simulation capture...', 'success');
                    
                    // Étape 2: Simuler une capture réussie
                    setTimeout(() => {
                        const fakePaymentId = 'FAKE_CAPTURE_' + Date.now();
                        showNotification('🎉 Paiement simulé avec succès!', 'success');
                        
                        setTimeout(() => {
                            window.location.href = '/?page=valider_paiement&success=1&payment_id=' + fakePaymentId + '&reservation_id=' + reservationId;
                        }, 2000);
                    }, 1000);
                }
            })
            .catch(error => {
                showNotification('❌ Erreur: ' + error.message, 'error');
            });
        });
    </script>
</body>
</html>