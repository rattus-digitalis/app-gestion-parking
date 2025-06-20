<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Zenpark</title>
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
        
        <div id="paypal-button-container"></div>
        
        <button id="pay-btn" class="btn">
            Payer <?= number_format((float)($_GET['montant'] ?? 0), 2, ',', ' ') ?> €
        </button>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="/?page=mes_reservations">← Retour</a>
        </p>
    </div>

    <script>
        const reservationId = <?= json_encode($_GET['id'] ?? null) ?>;
        const montant = <?= json_encode((float)($_GET['montant'] ?? 0)) ?>;
        
        function showNotification(message, type) {
            const notif = document.getElementById('notification');
            notif.textContent = message;
            notif.className = `notification ${type}`;
            notif.style.display = 'block';
            
            if (type !== 'error') {
                setTimeout(() => notif.style.display = 'none', 5000);
            }
        }
        
        // Validation simple
        if (!reservationId || !montant || montant <= 0) {
            showNotification('❌ Paramètres invalides', 'error');
        }
        
        // PayPal - teste d'abord si accessible
        document.getElementById('pay-btn').addEventListener('click', function() {
            showNotification('🔄 Création de la commande...', 'info');
            
            fetch('/?page=paiement&action=creer_ordre', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    reservation_id: reservationId,
                    amount: montant
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Response text:', text);
                try {
                    const data = JSON.parse(text);
                    if (data.error) {
                        showNotification('❌ ' + data.error, 'error');
                    } else if (data.order_id) {
                        showNotification('✅ Commande créée: ' + data.order_id, 'success');
                        // Ici on pourrait rediriger vers PayPal ou continuer
                    }
                } catch (e) {
                    showNotification('❌ Erreur de communication: ' + e.message, 'error');
                    console.error('Parse error:', e);
                }
            })
            .catch(error => {
                showNotification('❌ Erreur réseau: ' + error.message, 'error');
                console.error('Fetch error:', error);
            });
        });
    </script>
</body>
</html>