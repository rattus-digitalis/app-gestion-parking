<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include 'app/views/templates/head.php'; ?>
    <title>Paiement confirmé - Zenpark</title>
    <style>
        .confirmation-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }
        
        .payment-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.25rem 0;
        }
        
        .detail-row:last-child {
            margin-bottom: 0;
            border-top: 2px solid #dee2e6;
            padding-top: 0.75rem;
            font-weight: bold;
        }
        
        .actions {
            margin-top: 2rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            margin: 0.5rem;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #007bff;
            animation: confetti-fall 3s linear infinite;
        }
        
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
    </style>
</head>
<body data-page="valider_paiement">
    <?php include 'app/views/templates/nav.php'; ?>
    
    <div class="confirmation-container">
        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <!-- Paiement réussi -->
            <div class="success-icon">🎉</div>
            <h1>Paiement confirmé !</h1>
            
            <div class="alert alert-success">
                <strong>✅ Félicitations !</strong> Votre paiement a été traité avec succès. Votre réservation est maintenant confirmée.
            </div>
            
            <div class="payment-details">
                <h3>📋 Détails de votre paiement</h3>
                <div class="detail-row">
                    <span>Numéro de réservation :</span>
                    <span><strong>#<?= htmlspecialchars($_GET['reservation_id'] ?? 'N/A') ?></strong></span>
                </div>
                <div class="detail-row">
                    <span>ID de paiement PayPal :</span>
                    <span><?= htmlspecialchars(substr($_GET['payment_id'] ?? 'N/A', 0, 20)) ?>...</span>
                </div>
                <div class="detail-row">
                    <span>Date et heure :</span>
                    <span><?= date('d/m/Y à H:i') ?></span>
                </div>
                <div class="detail-row">
                    <span>Statut :</span>
                    <span style="color: #28a745;"><strong>✅ PAYÉ</strong></span>
                </div>
            </div>
            
            <div class="actions">
                <a href="/?page=mes_reservations" class="btn btn-primary">
                    📱 Voir mes réservations
                </a>
                <a href="/?page=dashboard_user" class="btn btn-secondary">
                    🏠 Retour au tableau de bord
                </a>
            </div>
            
        <?php elseif (isset($_GET['error'])): ?>
            <!-- Erreur de paiement -->
            <div class="success-icon" style="color: #dc3545;">❌</div>
            <h1>Paiement échoué</h1>
            
            <div class="alert alert-danger">
                <strong>⚠️ Oops !</strong> Une erreur est survenue lors du traitement de votre paiement. Aucun montant n'a été débité.
            </div>
            
            <div class="payment-details">
                <h3>🔍 Que faire maintenant ?</h3>
                <ul style="text-align: left; line-height: 1.6;">
                    <li>Vérifiez que vos informations de paiement sont correctes</li>
                    <li>Assurez-vous que votre compte PayPal dispose de fonds suffisants</li>
                    <li>Essayez avec une autre méthode de paiement</li>
                    <li>Contactez notre support si le problème persiste</li>
                </ul>
            </div>
            
            <div class="actions">
                <a href="/?page=paiement&id=<?= htmlspecialchars($_GET['reservation_id'] ?? '') ?>&montant=<?= htmlspecialchars($_GET['montant'] ?? '') ?>" class="btn btn-primary">
                    🔄 Réessayer le paiement
                </a>
                <a href="/?page=mes_reservations" class="btn btn-secondary">
                    📱 Mes réservations
                </a>
            </div>
            
        <?php else: ?>
            <!-- Accès direct sans paramètres -->
            <div class="success-icon" style="color: #ffc107;">⚠️</div>
            <h1>Page de validation</h1>
            
            <div class="alert alert-danger">
                <strong>Accès non autorisé.</strong> Cette page est accessible uniquement après un paiement.
            </div>
            
            <div class="actions">
                <a href="/?page=mes_reservations" class="btn btn-primary">
                    📱 Mes réservations
                </a>
                <a href="/?page=dashboard_user" class="btn btn-secondary">
                    🏠 Accueil
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Animation confetti pour les paiements réussis
        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        function createConfetti() {
            const colors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'];
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + 'vw';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 2 + 's';
                    confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 5000);
                }, i * 100);
            }
        }
        
        // Déclencher l'animation confetti après un court délai
        setTimeout(createConfetti, 500);
        
        // Notification de succès
        if (typeof showNotification === 'function') {
            showNotification('🎉 Paiement confirmé avec succès!', 'success');
        }
        <?php endif; ?>
        
        // Auto-redirection pour les erreurs après 10 secondes
        <?php if (isset($_GET['error'])): ?>
        let countdown = 10;
        const redirectTimer = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(redirectTimer);
                window.location.href = '/?page=mes_reservations';
            }
        }, 1000);
        <?php endif; ?>
    </script>
</body>
</html>