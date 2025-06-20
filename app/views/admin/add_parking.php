<?php
// app/views/admin/add_parking.php
require_once '../app/views/templates/head.php';
require_once '../app/views/templates/nav.php';
?>

<div class="container fade-in">
    <div class="card">
        <h1 class="section-title">Ajouter un nouveau parking</h1>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

<form method="POST" action="/?page=create_parking" class="slide-in" id="addParkingForm">
            <!-- Informations générales -->
            <div class="form-section">
                <h3 style="color: var(--text-accent); margin-bottom: var(--spacing-lg); font-size: var(--font-size-xl);">
                    📍 Informations générales
                </h3>
                
                <div class="form-group">
                    <label for="nom" class="form-label">Nom du parking *</label>
                    <input type="text" class="form-input" id="nom" name="nom" 
                           value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" 
                           placeholder="Ex: Parking Centre-Ville" required>
                    <div class="form-error" id="nom-error"></div>
                </div>

                <div class="form-group">
                    <label for="adresse" class="form-label">Adresse complète *</label>
                    <textarea class="form-textarea" id="adresse" name="adresse" 
                              rows="3" placeholder="Adresse complète du parking..." required><?= htmlspecialchars($_POST['adresse'] ?? '') ?></textarea>
                    <div class="form-error" id="adresse-error"></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);">
                    <div class="form-group">
                        <label for="ville" class="form-label">Ville *</label>
                        <input type="text" class="form-input" id="ville" name="ville" 
                               value="<?= htmlspecialchars($_POST['ville'] ?? '') ?>" 
                               placeholder="Ex: Paris" required>
                        <div class="form-error" id="ville-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="code_postal" class="form-label">Code postal *</label>
                        <input type="text" class="form-input" id="code_postal" name="code_postal" 
                               value="<?= htmlspecialchars($_POST['code_postal'] ?? '') ?>" 
                               placeholder="Ex: 75001" pattern="[0-9]{5}" maxlength="5" required>
                        <div class="form-error" id="code_postal-error"></div>
                    </div>
                </div>
            </div>

            <!-- Capacité et disponibilité -->
            <div class="form-section">
                <h3 style="color: var(--text-accent); margin-bottom: var(--spacing-lg); font-size: var(--font-size-xl);">
                    🚗 Capacité et disponibilité
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);">
                    <div class="form-group">
                        <label for="places_totales" class="form-label">Places totales *</label>
                        <input type="number" class="form-input" id="places_totales" name="places_totales" 
                               value="<?= htmlspecialchars($_POST['places_totales'] ?? '') ?>" 
                               min="1" max="9999" placeholder="Ex: 150" required>
                        <div class="form-error" id="places_totales-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="places_disponibles" class="form-label">Places disponibles *</label>
                        <input type="number" class="form-input" id="places_disponibles" name="places_disponibles" 
                               value="<?= htmlspecialchars($_POST['places_disponibles'] ?? '') ?>" 
                               min="0" placeholder="Ex: 120" required>
                        <div class="form-error" id="places_disponibles-error"></div>
                    </div>
                </div>

                <!-- Aperçu visuel de l'occupation -->
                <div class="form-group">
                    <label class="form-label">Aperçu de l'occupation</label>
                    <div class="availability-card" style="margin: 0; opacity: 0.7;" id="preview-card">
                        <div class="availability-info">
                            <div class="availability-icon">🚗</div>
                            <div class="availability-text">
                                <span class="places-count" id="preview-count">0 / 0</span>
                                <span class="occupation-rate">Places disponibles</span>
                            </div>
                        </div>
                        <div class="availability-indicator success" id="preview-indicator"></div>
                    </div>
                    <div class="occupation-bar">
                        <div class="occupation-fill" id="preview-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Tarification -->
            <div class="form-section">
                <h3 style="color: var(--text-accent); margin-bottom: var(--spacing-lg); font-size: var(--font-size-xl);">
                    💰 Tarification
                </h3>
                
                <div class="form-group">
                    <label for="tarif_horaire" class="form-label">Tarif horaire (€) *</label>
                    <input type="number" class="form-input" id="tarif_horaire" name="tarif_horaire" 
                           value="<?= htmlspecialchars($_POST['tarif_horaire'] ?? '') ?>" 
                           step="0.01" min="0" max="999.99" placeholder="Ex: 2.50" required>
                    <div class="form-error" id="tarif_horaire-error"></div>
                    <small style="color: var(--text-muted); font-size: var(--font-size-sm);">
                        💡 Tarif appliqué par heure de stationnement
                    </small>
                </div>

                <!-- Calculateur de revenus estimés -->
                <div class="stats-grid" style="margin-top: var(--spacing-lg);">
                    <div class="stat-item">
                        <span class="stat-number" id="revenue-day">0€</span>
                        <span class="stat-label">Revenus/jour estimés</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="revenue-month">0€</span>
                        <span class="stat-label">Revenus/mois estimés</span>
                    </div>
                </div>
            </div>

            <!-- Description et options -->
            <div class="form-section">
                <h3 style="color: var(--text-accent); margin-bottom: var(--spacing-lg); font-size: var(--font-size-xl);">
                    📝 Description et options
                </h3>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description du parking</label>
                    <textarea class="form-textarea" id="description" name="description" 
                              rows="4" placeholder="Décrivez les spécificités de ce parking (accès, équipements, etc.)..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    <small style="color: var(--text-muted); font-size: var(--font-size-sm);">
                        💡 Cette description sera visible par les utilisateurs
                    </small>
                </div>

                <div class="form-group">
                    <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                        <input type="checkbox" id="actif" name="actif" value="1" checked>
                        <label for="actif" class="form-label" style="margin: 0; cursor: pointer;">
                            ✅ Parking actif (disponible pour les réservations)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="reservation-actions" style="border-top: 1px solid var(--border-color); padding-top: var(--spacing-lg);">
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    <span>➕</span>
                    Créer le parking
                </button>
                <button type="reset" class="btn btn-outline" id="reset-btn">
                    <span>🔄</span>
                    Réinitialiser
                </button>
                <a href="/?page=parkings_list" class="btn btn-secondary">
                    <span>↩️</span>
                    Retour à la liste
                </a>
            </div>
        </form>

        <!-- Carte d'aide -->
        <div class="card" style="margin-top: var(--spacing-xl); background: var(--bg-tertiary);">
            <h3 style="color: var(--text-accent); margin-bottom: var(--spacing-md);">💡 Conseils pour ajouter un parking</h3>
            <div class="feature-grid">
                <div class="feature-card" style="background: var(--bg-card); border: none;">
                    <div class="feature-icon" style="color: var(--primary-color);">📍</div>
                    <h4 style="margin: 0 0 var(--spacing-sm) 0;">Localisation précise</h4>
                    <p style="margin: 0; font-size: var(--font-size-sm);">Indiquez une adresse complète pour faciliter la localisation</p>
                </div>
                <div class="feature-card" style="background: var(--bg-card); border: none;">
                    <div class="feature-icon" style="color: var(--success-color);">🚗</div>
                    <h4 style="margin: 0 0 var(--spacing-sm) 0;">Capacité réaliste</h4>
                    <p style="margin: 0; font-size: var(--font-size-sm);">Assurez-vous que le nombre de places correspond à la réalité</p>
                </div>
                <div class="feature-card" style="background: var(--bg-card); border: none;">
                    <div class="feature-icon" style="color: var(--warning-color);">💰</div>
                    <h4 style="margin: 0 0 var(--spacing-sm) 0;">Tarif compétitif</h4>
                    <p style="margin: 0; font-size: var(--font-size-sm);">Analysez les prix du secteur pour fixer un tarif attractif</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addParkingForm');
    const placesTotales = document.getElementById('places_totales');
    const placesDisponibles = document.getElementById('places_disponibles');
    const tarifHoraire = document.getElementById('tarif_horaire');
    const codePostal = document.getElementById('code_postal');
    
    // Éléments d'aperçu
    const previewCount = document.getElementById('preview-count');
    const previewBar = document.getElementById('preview-bar');
    const previewIndicator = document.getElementById('preview-indicator');
    const previewCard = document.getElementById('preview-card');
    
    // Éléments de revenus
    const revenueDay = document.getElementById('revenue-day');
    const revenueMonth = document.getElementById('revenue-month');
    
    // Mise à jour de l'aperçu en temps réel
    function updatePreview() {
        const total = parseInt(placesTotales.value) || 0;
        const disponibles = parseInt(placesDisponibles.value) || 0;
        const occupees = total - disponibles;
        const taux = total > 0 ? (occupees / total) * 100 : 0;
        
        // Mise à jour de l'affichage
        previewCount.textContent = `${disponibles} / ${total}`;
        previewBar.style.width = taux + '%';
        
        // Mise à jour de l'indicateur
        previewIndicator.className = 'availability-indicator ' + (taux > 80 ? 'warning' : 'success');
        
        // Afficher/masquer la carte d'aperçu
        if (total > 0) {
            previewCard.style.opacity = '1';
            previewCard.style.transform = 'scale(1)';
        } else {
            previewCard.style.opacity = '0.7';
            previewCard.style.transform = 'scale(0.95)';
        }
        
        updateRevenueEstimate();
    }
    
    // Calcul des revenus estimés
    function updateRevenueEstimate() {
        const total = parseInt(placesTotales.value) || 0;
        const tarif = parseFloat(tarifHoraire.value) || 0;
        
        if (total > 0 && tarif > 0) {
            // Estimation basée sur un taux d'occupation moyen de 60% et 8h/jour
            const revenueJour = Math.round(total * 0.6 * 8 * tarif);
            const revenueMois = Math.round(revenueJour * 30);
            
            revenueDay.textContent = revenueJour + '€';
            revenueMonth.textContent = revenueMois + '€';
        } else {
            revenueDay.textContent = '0€';
            revenueMonth.textContent = '0€';
        }
    }
    
    // Validation en temps réel
    function validateField(field, errorId, validationFn) {
        const errorElement = document.getElementById(errorId);
        const result = validationFn(field.value);
        
        if (result.valid) {
            field.style.borderColor = 'var(--success-color)';
            field.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
            errorElement.textContent = '';
        } else {
            field.style.borderColor = 'var(--danger-color)';
            field.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
            errorElement.textContent = result.message;
        }
        
        return result.valid;
    }
    
    // Fonctions de validation
    const validations = {
        places_totales: (value) => {
            const num = parseInt(value);
            if (!value || num < 1) return { valid: false, message: 'Le nombre de places doit être au moins 1' };
            if (num > 9999) return { valid: false, message: 'Le nombre de places ne peut pas dépasser 9999' };
            return { valid: true };
        },
        
        places_disponibles: (value) => {
            const disponibles = parseInt(value);
            const totales = parseInt(placesTotales.value) || 0;
            if (!value || disponibles < 0) return { valid: false, message: 'Le nombre de places disponibles ne peut pas être négatif' };
            if (disponibles > totales) return { valid: false, message: 'Les places disponibles ne peuvent pas dépasser les places totales' };
            return { valid: true };
        },
        
        code_postal: (value) => {
            if (!value || !/^[0-9]{5}$/.test(value)) return { valid: false, message: 'Le code postal doit contenir exactement 5 chiffres' };
            return { valid: true };
        },
        
        tarif_horaire: (value) => {
            const num = parseFloat(value);
            if (!value || num < 0) return { valid: false, message: 'Le tarif doit être positif' };
            if (num > 999.99) return { valid: false, message: 'Le tarif ne peut pas dépasser 999.99€' };
            return { valid: true };
        }
    };
    
    // Ajout des écouteurs d'événements
    placesTotales.addEventListener('input', () => {
        validateField(placesTotales, 'places_totales-error', validations.places_totales);
        updatePreview();
    });
    
    placesDisponibles.addEventListener('input', () => {
        validateField(placesDisponibles, 'places_disponibles-error', validations.places_disponibles);
        updatePreview();
    });
    
    tarifHoraire.addEventListener('input', () => {
        validateField(tarifHoraire, 'tarif_horaire-error', validations.tarif_horaire);
        updateRevenueEstimate();
    });
    
    codePostal.addEventListener('input', () => {
        validateField(codePostal, 'code_postal-error', validations.code_postal);
    });
    
    // Auto-remplissage intelligent
    placesTotales.addEventListener('input', function() {
        if (this.value && !placesDisponibles.value) {
            // Remplir automatiquement avec 80% de la capacité
            placesDisponibles.value = Math.floor(parseInt(this.value) * 0.8);
            updatePreview();
        }
    });
    
    // Animations sur les inputs
    const inputs = document.querySelectorAll('.form-input, .form-textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'translateY(-1px)';
            this.style.boxShadow = 'var(--shadow-md), 0 0 0 3px rgba(99, 102, 241, 0.1)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = '';
            if (!this.matches(':focus')) {
                this.style.boxShadow = '';
            }
        });
    });
    
    // Gestion de la soumission du formulaire
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validation de tous les champs
        Object.keys(validations).forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field && !validateField(field, fieldName + '-error', validations[fieldName])) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            e.stopPropagation();
            
            // Animation d'erreur
            form.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                form.style.animation = '';
            }, 500);
        } else {
            // Animation de succès
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.innerHTML = '<span>⏳</span> Création en cours...';
            submitBtn.disabled = true;
        }
    });
    
    // Bouton de réinitialisation
    document.getElementById('reset-btn').addEventListener('click', function() {
        setTimeout(() => {
            updatePreview();
            updateRevenueEstimate();
            
            // Réinitialiser les styles des champs
            inputs.forEach(input => {
                input.style.borderColor = '';
                input.style.boxShadow = '';
            });
            
            // Effacer les messages d'erreur
            document.querySelectorAll('.form-error').forEach(error => {
                error.textContent = '';
            });
        }, 10);
    });
    
    // Animation d'entrée pour les sections
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'slideIn 0.6s ease-out';
                observer.unobserve(entry.target);
            }
        });
    });
    
    document.querySelectorAll('.form-section').forEach(section => {
        observer.observe(section);
    });
    
    // Initialisation
    updatePreview();
    updateRevenueEstimate();
});
</script>

<style>
/* Styles spécifiques pour la page d'ajout */
.form-section {
    margin-bottom: var(--spacing-2xl);
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--border-color);
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h3 {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

/* Animation pour les sections */
.form-section {
    opacity: 0;
    transform: translateY(20px);
}

/* Styles pour les statistiques de revenus */
.stat-item {
    transition: all var(--transition-normal);
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Responsive design */
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
        gap: var(--spacing-md) !important;
    }
    
    .reservation-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        text-align: center;
    }
    
    .feature-grid {
        grid-template-columns: 1fr;
    }
}

/* Animation shake pour les erreurs */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

/* Style pour le checkbox */
input[type="checkbox"] {
    appearance: none;
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-sm);
    background: var(--bg-secondary);
    cursor: pointer;
    position: relative;
    transition: all var(--transition-fast);
}

input[type="checkbox"]:checked {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

input[type="checkbox"]:checked::before {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 0.875rem;
    font-weight: bold;
}

input[type="checkbox"]:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

/* Styles pour les messages d'erreur */
.form-error {
    color: var(--danger-color);
    font-size: var(--font-size-sm);
    margin-top: var(--spacing-xs);
    min-height: 1.2rem;
    transition: all var(--transition-fast);
}

/* Animation pour l'aperçu */
#preview-card {
    transition: all var(--transition-normal);
}

/* Amélioration de l'accessibilité */
.form-input:focus,
.form-textarea:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

/* Styles pour les conseils */
.feature-card h4 {
    color: var(--text-accent);
    font-size: var(--font-size-base);
}

.feature-card p {
    color: var(--text-secondary);
}
</style>

<?php require_once '../app/views/templates/footer.php'; ?>