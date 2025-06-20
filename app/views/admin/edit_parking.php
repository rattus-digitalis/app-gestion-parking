<?php
// app/views/admin/edit_parking.php
require_once '../app/views/templates/head.php';
require_once '../app/views/templates/nav.php';
?>

<div class="container fade-in">
    <div class="card">
        <h1 class="section-title">Modifier la place de parking</h1>
        
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

        <form method="POST" action="/?page=edit_parking&id=<?= $parking['id'] ?>" class="slide-in" id="editParkingForm">
            <!-- Informations de la place -->
            <div class="form-section">
                <h3 style="color: var(--text-accent); margin-bottom: var(--spacing-lg); font-size: var(--font-size-xl);">
                    🅿️ Informations de la place
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);">
                    <div class="form-group">
                        <label for="numero_place" class="form-label">Numéro de place *</label>
                        <input type="text" class="form-input" id="numero_place" name="numero_place" 
                               value="<?= htmlspecialchars($parking['numero_place'] ?? '') ?>" 
                               placeholder="Ex: A12, B05, 101" required>
                        <div class="form-error" id="numero_place-error"></div>
                        <small style="color: var(--text-muted); font-size: var(--font-size-sm);">
                            💡 Identifiant unique de la place
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="etage" class="form-label">Étage/Niveau</label>
                        <select class="form-select" id="etage" name="etage">
                            <option value="0" <?= ($parking['etage'] ?? '0') == '0' ? 'selected' : '' ?>>Rez-de-chaussée (0)</option>
                            <option value="1" <?= ($parking['etage'] ?? '') == '1' ? 'selected' : '' ?>>Étage 1</option>
                            <option value="2" <?= ($parking['etage'] ?? '') == '2' ? 'selected' : '' ?>>Étage 2</option>
                            <option value="3" <?= ($parking['etage'] ?? '') == '3' ? 'selected' : '' ?>>Étage 3</option>
                            <option value="-1" <?= ($parking['etage'] ?? '') == '-1' ? 'selected' : '' ?>>Sous-sol -1</option>
                            <option value="-2" <?= ($parking['etage'] ?? '') == '-2' ? 'selected' : '' ?>>Sous-sol -2</option>
                            <option value="-3" <?= ($parking['etage'] ?? '') == '-3' ? 'selected' : '' ?>>Sous-sol -3</option>
                        </select>
                        <div class="form-error" id="etage-error"></div>
                    </div>
                </div>
            </div>

            <!-- Type et statut -->
            <div class="form-section">
                <h3 style="color: var(--text-accent); margin-bottom: var(--spacing-lg); font-size: var(--font-size-xl);">
                    🚗 Type et statut
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);">
                    <div class="form-group">
                        <label for="type_place" class="form-label">Type de place *</label>
                        <select class="form-select" id="type_place" name="type_place" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="standard" <?= ($parking['type_place'] ?? '') === 'standard' ? 'selected' : '' ?>>🚗 Standard</option>
                            <option value="handicap" <?= ($parking['type_place'] ?? '') === 'handicap' ? 'selected' : '' ?>>♿ Handicapé</option>
                            <option value="electrique" <?= ($parking['type_place'] ?? '') === 'electrique' ? 'selected' : '' ?>>⚡ Véhicule électrique</option>
                            <option value="moto" <?= ($parking['type_place'] ?? '') === 'moto' ? 'selected' : '' ?>>🏍️ Moto/Scooter</option>
                        </select>
                        <div class="form-error" id="type_place-error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="statut" class="form-label">Statut actuel</label>
                        <select class="form-select" id="statut" name="statut">
                            <option value="libre" <?= ($parking['statut'] ?? 'libre') === 'libre' ? 'selected' : '' ?>>✅ Libre</option>
                            <option value="occupe" <?= ($parking['statut'] ?? '') === 'occupe' ? 'selected' : '' ?>>🚗 Occupée</option>
                            <option value="reserve" <?= ($parking['statut'] ?? '') === 'reserve' ? 'selected' : '' ?>>📅 Réservée</option>
                            <option value="maintenance" <?= ($parking['statut'] ?? '') === 'maintenance' ? 'selected' : '' ?>>🔧 En maintenance</option>
                        </select>
                        <div class="form-error" id="statut-error"></div>
                    </div>
                </div>

                <!-- Aperçu visuel du statut -->
                <div class="form-group">
                    <label class="form-label">Aperçu du statut</label>
                    <div class="status-preview-card" id="status-preview">
                        <div class="status-icon" id="preview-icon">✅</div>
                        <div class="status-text">
                            <span class="status-label" id="preview-label">Place libre</span>
                            <span class="status-description" id="preview-description">Disponible pour réservation</span>
                        </div>
                        <div class="status-indicator" id="preview-indicator"></div>
                    </div>
                </div>
            </div>

            <!-- Disponibilité -->
            <div class="form-section">
                <h3 style="color: var(--text-accent); margin-bottom: var(--spacing-lg); font-size: var(--font-size-xl);">
                    📅 Disponibilité
                </h3>
                
                <div class="form-group">
                    <label for="disponible_depuis" class="form-label">Disponible depuis</label>
                    <input type="datetime-local" class="form-input" id="disponible_depuis" name="disponible_depuis" 
                           value="<?= isset($parking['disponible_depuis']) ? date('Y-m-d\TH:i', strtotime($parking['disponible_depuis'])) : '' ?>">
                    <div class="form-error" id="disponible_depuis-error"></div>
                    <small style="color: var(--text-muted); font-size: var(--font-size-sm);">
                        💡 Date et heure à partir de laquelle cette place est disponible
                    </small>
                </div>

                <div class="form-group">
                    <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                        <input type="checkbox" id="actif" name="actif" value="1"
                               <?= (isset($parking['actif']) && $parking['actif']) ? 'checked' : '' ?>>
                        <label for="actif" class="form-label" style="margin: 0; cursor: pointer;">
                            ✅ Place active (disponible pour les réservations)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Commentaires et notes -->
            <div class="form-section">
                <h3 style="color: var(--text-accent); margin-bottom: var(--spacing-lg); font-size: var(--font-size-xl);">
                    📝 Informations complémentaires
                </h3>
                
                <div class="form-group">
                    <label for="commentaire" class="form-label">Commentaire/Notes</label>
                    <textarea class="form-textarea" id="commentaire" name="commentaire" 
                              rows="3" placeholder="Informations particulières sur cette place (taille, accès, équipements...)"><?= htmlspecialchars($parking['commentaire'] ?? '') ?></textarea>
                    <small style="color: var(--text-muted); font-size: var(--font-size-sm);">
                        💡 Ces informations sont visibles pour la gestion interne
                    </small>
                </div>

                <!-- Informations système -->
                <?php if (isset($parking['date_maj'])): ?>
                <div class="form-group">
                    <label class="form-label">Dernière modification</label>
                    <div style="background: var(--bg-tertiary); padding: var(--spacing-sm); border-radius: var(--radius-md); color: var(--text-muted);">
                        📅 <?= date('d/m/Y à H:i', strtotime($parking['date_maj'])) ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (isset($parking['derniere_reservation_id']) && $parking['derniere_reservation_id']): ?>
                <div class="form-group">
                    <label class="form-label">Dernière réservation</label>
                    <div style="background: var(--bg-tertiary); padding: var(--spacing-sm); border-radius: var(--radius-md); color: var(--text-muted);">
                        🎫 Réservation #<?= $parking['derniere_reservation_id'] ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="reservation-actions" style="border-top: 1px solid var(--border-color); padding-top: var(--spacing-lg);">
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    <span>💾</span>
                    Mettre à jour la place
                </button>
                <button type="reset" class="btn btn-outline" id="reset-btn">
                    <span>🔄</span>
                    Réinitialiser
                </button>
                <a href="/?page=admin_parkings" class="btn btn-secondary">
                    <span>↩️</span>
                    Retour à la liste
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editParkingForm');
    const numeroPlace = document.getElementById('numero_place');
    const typePlace = document.getElementById('type_place');
    const statut = document.getElementById('statut');
    
    // Éléments d'aperçu du statut
    const previewIcon = document.getElementById('preview-icon');
    const previewLabel = document.getElementById('preview-label');
    const previewDescription = document.getElementById('preview-description');
    const previewIndicator = document.getElementById('preview-indicator');
    
    // Configuration des statuts (selon votre BDD)
    const statusConfig = {
        'libre': {
            icon: '✅',
            label: 'Place libre',
            description: 'Disponible pour réservation',
            class: 'success'
        },
        'occupe': {
            icon: '🚗',
            label: 'Place occupée',
            description: 'Actuellement utilisée',
            class: 'warning'
        },
        'reserve': {
            icon: '📅',
            label: 'Place réservée',
            description: 'Réservation en cours',
            class: 'info'
        },
        'maintenance': {
            icon: '🔧',
            label: 'En maintenance',
            description: 'Temporairement indisponible',
            class: 'warning'
        }
    };
    
    // Mise à jour de l'aperçu du statut
    function updateStatusPreview() {
        const currentStatus = statut.value || 'libre';
        const config = statusConfig[currentStatus];
        
        if (config) {
            previewIcon.textContent = config.icon;
            previewLabel.textContent = config.label;
            previewDescription.textContent = config.description;
            previewIndicator.className = `status-indicator ${config.class}`;
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
        numero_place: (value) => {
            if (!value || value.trim().length < 1) {
                return { valid: false, message: 'Le numéro de place est obligatoire' };
            }
            if (value.length > 10) {
                return { valid: false, message: 'Le numéro ne peut pas dépasser 10 caractères' };
            }
            return { valid: true };
        },
        
        type_place: (value) => {
            const validTypes = ['standard', 'handicap', 'electrique', 'moto'];
            if (!value) {
                return { valid: false, message: 'Le type de place est obligatoire' };
            }
            if (!validTypes.includes(value)) {
                return { valid: false, message: 'Type de place invalide' };
            }
            return { valid: true };
        }
    };
    
    // Écouteurs d'événements
    statut.addEventListener('change', updateStatusPreview);
    
    numeroPlace.addEventListener('input', () => {
        validateField(numeroPlace, 'numero_place-error', validations.numero_place);
    });
    
    typePlace.addEventListener('change', () => {
        validateField(typePlace, 'type_place-error', validations.type_place);
    });
    
    // Auto-formatting du numéro de place
    numeroPlace.addEventListener('input', function() {
        // Convertir en majuscules et supprimer les caractères non autorisés
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });
    
    // Animations sur les inputs
    const inputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
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
            submitBtn.innerHTML = '<span>⏳</span> Mise à jour en cours...';
            submitBtn.disabled = true;
        }
    });
    
    // Bouton de réinitialisation
    document.getElementById('reset-btn').addEventListener('click', function() {
        setTimeout(() => {
            updateStatusPreview();
            
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
    
    // Initialisation
    updateStatusPreview();
});
</script>

<style>
/* Styles spécifiques pour la page d'édition de place */
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

/* Correction pour la visibilité */
.container.fade-in,
.form-section,
#editParkingForm.slide-in {
    opacity: 1 !important;
    transform: none !important;
    display: block !important;
    visibility: visible !important;
}

/* Aperçu du statut */
.status-preview-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: var(--spacing-md);
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    transition: all var(--transition-normal);
}

.status-icon {
    font-size: 1.5rem;
}

.status-text {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
    flex: 1;
}

.status-label {
    font-weight: 600;
    color: var(--text-accent);
}

.status-description {
    font-size: var(--font-size-sm);
    color: var(--text-muted);
}

.status-indicator {
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    box-shadow: 0 0 8px currentColor;
}

.status-indicator.success {
    background: var(--success-color);
    color: var(--success-color);
}

.status-indicator.warning {
    background: var(--warning-color);
    color: var(--warning-color);
}

.status-indicator.info {
    background: var(--info-color);
    color: var(--info-color);
}

.status-indicator.danger {
    background: var(--danger-color);
    color: var(--danger-color);
}

/* Style pour les select */
.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
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

/* Amélioration de l'accessibilité */
.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}
</style>

<?php require_once '../app/views/templates/footer.php'; ?>