<?php
$title = "Ma voiture";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container" role="main">
    <div class="fade-in">
        <h1 class="section-title">Mon véhicule</h1>
        
        <!-- Informations véhicule actuel -->
        <?php if (isset($car) && !empty($car)): ?>
        <div class="card mb-4">
            <div class="availability-info">
                <div class="availability-icon">
                    <?= $car['type'] === 'moto' ? '🏍️' : '🚗' ?>
                </div>
                <div class="availability-text">
                    <div class="places-count"><?= htmlspecialchars($car['marque'] ?? 'Véhicule') ?></div>
                    <div class="occupation-rate">
                        <?= htmlspecialchars($car['immatriculation'] ?? '') ?> • 
                        <?= htmlspecialchars($car['couleur'] ?? '') ?>
                    </div>
                </div>
            </div>
            <div class="availability-indicator success"></div>
        </div>
        <?php endif; ?>

        <!-- Statistiques du véhicule -->
        <div class="stats-grid mb-5">
            <div class="stat-item">
                <span class="stat-number">12</span>
                <span class="stat-label">Réservations</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">3</span>
                <span class="stat-label">Ce mois</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">4.8</span>
                <span class="stat-label">Note moyenne</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">98%</span>
                <span class="stat-label">Disponibilité</span>
            </div>
        </div>

        <!-- Formulaire de modification -->
        <div class="card">
            <h2>
                <?= isset($car) && !empty($car) ? 'Modifier mon véhicule' : 'Ajouter mon véhicule' ?>
            </h2>
            <p class="text-secondary mb-4">
                Renseignez les informations de votre véhicule pour les réservations de places de parking.
            </p>
            
            <form method="POST" action="/?page=ma_voiture" class="slide-in">
                <div class="form-group">
                    <label for="marque" class="form-label">
                        <span>🏷️</span> Marque du véhicule
                    </label>
                    <input 
                        type="text" 
                        id="marque" 
                        name="marque" 
                        class="form-input"
                        value="<?= htmlspecialchars($car['marque'] ?? '') ?>" 
                        required
                        placeholder="Ex: Peugeot, Renault, BMW..."
                        list="marques-list"
                    >
                    <datalist id="marques-list">
                        <option value="Peugeot">
                        <option value="Renault">
                        <option value="Citroën">
                        <option value="BMW">
                        <option value="Mercedes">
                        <option value="Audi">
                        <option value="Volkswagen">
                        <option value="Toyota">
                        <option value="Honda">
                        <option value="Yamaha">
                        <option value="Kawasaki">
                        <option value="Autre">

                    </datalist>
                </div>

                <div class="form-group">
                    <label for="immatriculation" class="form-label">
                        <span>🔢</span> Numéro d'immatriculation
                    </label>
                    <input 
                        type="text" 
                        id="immatriculation" 
                        name="immatriculation" 
                        class="form-input"
                        value="<?= htmlspecialchars($car['immatriculation'] ?? '') ?>" 
                        required
                        placeholder="Ex: AB-123-CD"
                        pattern="[A-Z]{2}-[0-9]{3}-[A-Z]{2}"
                        title="Format attendu: AB-123-CD"
                        style="text-transform: uppercase;"
                    >
                    <small class="form-help">Format français: AB-123-CD</small>
                </div>

                <div class="form-group">
                    <label for="couleur" class="form-label">
                        <span>🎨</span> Couleur principale
                    </label>
                    <input 
                        type="text" 
                        id="couleur" 
                        name="couleur" 
                        class="form-input"
                        value="<?= htmlspecialchars($car['couleur'] ?? '') ?>" 
                        required
                        placeholder="Ex: Blanc, Noir, Rouge..."
                        list="couleurs-list"
                    >
                    <datalist id="couleurs-list">
                        <option value="Blanc">
                        <option value="Noir">
                        <option value="Gris">
                        <option value="Rouge">
                        <option value="Bleu">
                        <option value="Vert">
                        <option value="Jaune">
                        <option value="Orange">
                        <option value="Violet">
                        <option value="Marron">
                    </datalist>
                </div>

                <div class="form-group">
                    <label for="type" class="form-label">
                        <span>🚗</span> Type de véhicule
                    </label>
                    <select id="type" name="type" class="form-select" required>
                        <option value="">Sélectionnez le type</option>
                        <option value="voiture" <?= (isset($car['type']) && $car['type'] === 'voiture') ? 'selected' : '' ?>>
                            🚗 Voiture
                        </option>
                        <option value="moto" <?= (isset($car['type']) && $car['type'] === 'moto') ? 'selected' : '' ?>>
                            🏍️ Moto
                        </option>
                        <option value="scooter" <?= (isset($car['type']) && $car['type'] === 'scooter') ? 'selected' : '' ?>>
                            🛵 Scooter
                        </option>
                        <option value="camionnette" <?= (isset($car['type']) && $car['type'] === 'camionnette') ? 'selected' : '' ?>>
                            🚐 Camionnette
                        </option>
                    </select>
                </div>

                <!-- Champs supplémentaires optionnels -->
                <div class="form-group">
                    <label for="modele" class="form-label">
                        <span>📋</span> Modèle (optionnel)
                    </label>
                    <input 
                        type="text" 
                        id="modele" 
                        name="modele" 
                        class="form-input"
                        value="<?= htmlspecialchars($car['modele'] ?? '') ?>" 
                        placeholder="Ex: 308, Clio, Serie 3..."
                    >
                </div>

                <div class="form-group">
                    <label for="annee" class="form-label">
                        <span>📅</span> Année (optionnel)
                    </label>
                    <input 
                        type="number" 
                        id="annee" 
                        name="annee" 
                        class="form-input"
                        value="<?= htmlspecialchars($car['annee'] ?? '') ?>" 
                        min="1950" 
                        max="<?= date('Y') + 1 ?>"
                        placeholder="<?= date('Y') ?>"
                    >
                </div>

                <div class="reservation-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span>💾</span> 
                        <?= isset($car) && !empty($car) ? 'Mettre à jour' : 'Enregistrer mon véhicule' ?>
                    </button>
                    <a href="/?page=dashboard_user" class="btn btn-secondary btn-lg">
                        <span>←</span> Retour au tableau de bord
                    </a>
                </div>
            </form>
        </div>

        <!-- Informations et conseils -->
        <div class="feature-grid mt-5">
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Sécurité</h3>
                <p>Vos informations de véhicule sont sécurisées et ne sont utilisées que pour les réservations.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📋</div>
                <h3>Identification</h3>
                <p>Ces informations aident les agents de parking à identifier votre véhicule facilement.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Réservation rapide</h3>
                <p>Une fois enregistré, vos futures réservations seront plus rapides à effectuer.</p>
            </div>
        </div>

        <!-- Historique des réservations pour ce véhicule -->
        <?php if (isset($car) && !empty($car)): ?>
        <div class="card mt-5">
            <h3>Historique des réservations</h3>
            <div class="occupation-bar">
                <div class="occupation-fill" style="width: 75%;"></div>
            </div>
            <p class="text-secondary mt-2">
                75% d'utilisation ce mois-ci • Dernière réservation il y a 2 jours
            </p>
            
            <div class="reservation-actions">
                <a href="/?page=mes_reservations" class="btn btn-info">
                    <span>📊</span> Voir toutes mes réservations
                </a>
                <a href="/?page=nouvelle_reservation" class="btn btn-success">
                    <span>➕</span> Nouvelle réservation
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Messages d'alerte -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <strong>Succès !</strong> Les informations de votre véhicule ont été enregistrées.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <strong>Erreur !</strong> Une erreur s'est produite lors de l'enregistrement.
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
/* Styles spécifiques pour la page véhicule */
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

/* Style pour les listes de suggestions */
.form-input[list]::-webkit-calendar-picker-indicator {
    opacity: 0.5;
}

/* Validation visuelle spécifique */
#immatriculation:valid {
    border-color: var(--success-color);
}

#immatriculation:invalid:not(:focus):not(:placeholder-shown) {
    border-color: var(--danger-color);
}

/* Animation pour les icônes de véhicule */
.availability-icon {
    font-size: 2.5rem;
    animation: pulse 2s ease-in-out infinite;
}

/* Style pour les types de véhicule */
.form-select option {
    background: var(--bg-secondary);
    color: var(--text-primary);
    padding: var(--spacing-sm);
}

/* Responsive */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-sm);
    }
    
    .feature-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const immatriculationInput = document.getElementById('immatriculation');
    const typeSelect = document.getElementById('type');
    
    // Formatage automatique de l'immatriculation
    immatriculationInput.addEventListener('input', function(e) {
        let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        
        // Format français: AB-123-CD
        if (value.length >= 2) {
            value = value.substring(0, 2) + '-' + value.substring(2);
        }
        if (value.length >= 6) {
            value = value.substring(0, 6) + '-' + value.substring(6, 8);
        }
        
        e.target.value = value;
    });
    
    // Changement d'icône selon le type de véhicule
    typeSelect.addEventListener('change', function() {
        const icon = document.querySelector('.availability-icon');
        if (icon) {
            switch(this.value) {
                case 'voiture':
                    icon.textContent = '🚗';
                    break;
                case 'moto':
                    icon.textContent = '🏍️';
                    break;
                case 'scooter':
                    icon.textContent = '🛵';
                    break;
                case 'camionnette':
                    icon.textContent = '🚐';
                    break;
                default:
                    icon.textContent = '🚗';
            }
        }
    });
    
    // Validation côté client
    form.addEventListener('submit', function(e) {
        const immatriculation = immatriculationInput.value;
        const pattern = /^[A-Z]{2}-[0-9]{3}-[A-Z]{2}$/;
        
        if (!pattern.test(immatriculation)) {
            e.preventDefault();
            alert('Format d\'immatriculation invalide. Format attendu: AB-123-CD');
            immatriculationInput.focus();
            return;
        }
        
        // Feedback visuel lors de la soumission
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="pulse">⏳</span> Enregistrement en cours...';
        
        // Réactiver le bouton après 5 secondes
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>💾</span> Enregistrer mon véhicule';
        }, 5000);
    });
    
    // Auto-complétion intelligente
    const marqueInput = document.getElementById('marque');
    const couleurInput = document.getElementById('couleur');
    
    // Capitaliser la première lettre
    [marqueInput, couleurInput].forEach(input => {
        input.addEventListener('blur', function() {
            this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();
        });
    });
    
    // Animation des champs au focus
    const inputs = document.querySelectorAll('.form-input, .form-select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.parentElement.classList.remove('focused');
            }
        });
        
        // Marquer comme focused si déjà rempli
        if (input.value !== '') {
            input.parentElement.classList.add('focused');
        }
    });
});
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>