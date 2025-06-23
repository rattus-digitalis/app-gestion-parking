<?php 
$title = "Mon compte"; 
require_once __DIR__ . '/../templates/head.php'; 
require_once __DIR__ . '/../templates/nav.php';  

$user = $_SESSION['user']; 
?>

<main class="container" role="main">
    <div class="fade-in">
        <h1 class="section-title">Mon compte</h1>
        
        <!-- Informations du compte -->
        <div class="card mb-4">
            <div class="availability-info">
                <div class="availability-icon">👤</div>
                <div class="availability-text">
                    <div class="places-count"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></div>
                    <div class="occupation-rate">Membre depuis <?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?></div>
                </div>
            </div>
            <div class="availability-indicator success"></div>
        </div>

        <!-- Statistiques du compte -->
        <div class="stats-grid mb-5">
            <div class="stat-item">
                <span class="stat-number">5</span>
                <span class="stat-label">Réservations</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">2</span>
                <span class="stat-label">En cours</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">3</span>
                <span class="stat-label">Terminées</span>
            </div>
        </div>

        <!-- Formulaire de modification -->
        <div class="card">
            <h2>Modifier mes informations</h2>
            <p class="text-secondary mb-4">Gérez ici les informations de votre compte utilisateur.</p>
            
            <form action="/?page=mon_compte" method="POST" class="slide-in">
                <div class="form-group">
                    <label for="first_name" class="form-label">
                        <span>👤</span> Prénom
                    </label>
                    <input 
                        type="text" 
                        id="first_name" 
                        name="first_name" 
                        class="form-input"
                        value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" 
                        required
                        placeholder="Votre prénom"
                    >
                </div>

                <div class="form-group">
                    <label for="last_name" class="form-label">
                        <span>👤</span> Nom de famille
                    </label>
                    <input 
                        type="text" 
                        id="last_name" 
                        name="last_name" 
                        class="form-input"
                        value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" 
                        required
                        placeholder="Votre nom de famille"
                    >
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <span>📧</span> Adresse email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input"
                        value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                        required
                        placeholder="votre@email.com"
                    >
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">
                        <span>📱</span> Numéro de téléphone
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        class="form-input"
                        value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                        placeholder="06 12 34 56 78"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <span>🔒</span> Nouveau mot de passe
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input"
                        placeholder="Laissez vide pour ne pas modifier"
                        minlength="6"
                    >
                    <small class="form-help">Minimum 6 caractères. Laissez vide si vous ne souhaitez pas modifier votre mot de passe.</small>
                </div>

                <div class="form-group">
                    <label for="password_confirm" class="form-label">
                        <span>🔒</span> Confirmer le nouveau mot de passe
                    </label>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        class="form-input"
                        placeholder="Confirmez votre nouveau mot de passe"
                    >
                </div>

                <div class="reservation-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span>💾</span> Mettre à jour mes informations
                    </button>
                    <a href="/?page=dashboard_user" class="btn btn-secondary btn-lg">
                        <span>←</span> Retour au tableau de bord
                    </a>
                </div>
            </form>
        </div>

        <!-- Actions rapides -->
        <div class="feature-grid mt-5">
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3>Sécurité</h3>
                <p>Modifiez votre mot de passe régulièrement pour sécuriser votre compte.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Notifications</h3>
                <p>Assurez-vous que vos coordonnées sont à jour pour recevoir nos communications.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Historique</h3>
                <p>Consultez l'historique de vos réservations dans votre tableau de bord.</p>
            </div>
        </div>

        <!-- Messages d'alerte (à afficher conditionnellement selon le contexte) -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <strong>Succès !</strong> Vos informations ont été mises à jour avec succès.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <strong>Erreur !</strong> Une erreur s'est produite lors de la mise à jour de vos informations.
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
/* Styles spécifiques pour la page Mon compte */
.form-help {
    display: block;
    font-size: var(--font-size-xs);
    color: var(--text-muted);
    margin-top: var(--spacing-xs);
}

.form-label span {
    margin-right: var(--spacing-xs);
    font-size: var(--font-size-sm);
}

.account-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.account-info-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: white;
    padding: var(--spacing-lg);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-xl);
    text-align: center;
}

.account-info-header h2 {
    margin: 0;
    font-size: var(--font-size-2xl);
}

.account-info-header p {
    margin: var(--spacing-sm) 0 0 0;
    opacity: 0.9;
}

/* Animation pour les champs de formulaire */
.form-input:focus + .form-label,
.form-input:not(:placeholder-shown) + .form-label {
    transform: translateY(-1.5rem) scale(0.8);
    color: var(--primary-color);
}

/* Validation visuelle */
.form-input:valid {
    border-color: var(--success-color);
}

.form-input:invalid:not(:focus):not(:placeholder-shown) {
    border-color: var(--danger-color);
}

/* Responsive pour mobile */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: var(--spacing-sm);
    }
    
    .feature-grid {
        grid-template-columns: 1fr;
    }
    
    .reservation-actions {
        flex-direction: column;
    }
    
    .btn-lg {
        padding: var(--spacing-md);
        font-size: var(--font-size-base);
    }
}
</style>

<script>
// Validation du formulaire côté client
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    
    // Validation des mots de passe
    function validatePasswords() {
        if (password.value !== '' && passwordConfirm.value !== '') {
            if (password.value !== passwordConfirm.value) {
                passwordConfirm.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                passwordConfirm.setCustomValidity('');
            }
        } else {
            passwordConfirm.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePasswords);
    passwordConfirm.addEventListener('input', validatePasswords);
    
    // Animation des champs au focus
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.parentElement.classList.remove('focused');
            }
        });
    });
    
    // Soumission du formulaire avec feedback visuel
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="pulse">⏳</span> Mise à jour en cours...';
        
        // Réactiver le bouton après 5 secondes pour éviter le blocage
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>💾</span> Mettre à jour mes informations';
        }, 5000);
    });
});
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>