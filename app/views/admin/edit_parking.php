<?php
// app/views/admin/edit_parking.php
require_once '../app/views/templates/head.php';
require_once '../app/views/templates/nav.php';
?>

<div class="container fade-in">
    <div class="card">
        <h1 class="section-title">Modifier le parking</h1>
        
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

        <form method="POST" action="/?page=edit_parking&id=<?= $parking['id'] ?>" class="slide-in">
            <div class="form-group">
                <label for="nom" class="form-label">Nom du parking *</label>
                <input type="text" class="form-input" id="nom" name="nom" 
                       value="<?= htmlspecialchars($parking['nom'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="adresse" class="form-label">Adresse *</label>
                <textarea class="form-textarea" id="adresse" name="adresse" 
                          rows="3" required><?= htmlspecialchars($parking['adresse'] ?? '') ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);">
                <div class="form-group">
                    <label for="ville" class="form-label">Ville *</label>
                    <input type="text" class="form-input" id="ville" name="ville" 
                           value="<?= htmlspecialchars($parking['ville'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="code_postal" class="form-label">Code postal *</label>
                    <input type="text" class="form-input" id="code_postal" name="code_postal" 
                           value="<?= htmlspecialchars($parking['code_postal'] ?? '') ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); margin-bottom: var(--spacing-lg);">
                <div class="form-group">
                    <label for="places_totales" class="form-label">Places totales *</label>
                    <input type="number" class="form-input" id="places_totales" name="places_totales" 
                           value="<?= htmlspecialchars($parking['places_totales'] ?? '') ?>" min="1" required>
                </div>
                <div class="form-group">
                    <label for="places_disponibles" class="form-label">Places disponibles *</label>
                    <input type="number" class="form-input" id="places_disponibles" name="places_disponibles" 
                           value="<?= htmlspecialchars($parking['places_disponibles'] ?? '') ?>" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label for="tarif_horaire" class="form-label">Tarif horaire (€) *</label>
                <input type="number" class="form-input" id="tarif_horaire" name="tarif_horaire" 
                       value="<?= htmlspecialchars($parking['tarif_horaire'] ?? '') ?>" 
                       step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-textarea" id="description" name="description" 
                          rows="4"><?= htmlspecialchars($parking['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
                    <input type="checkbox" id="actif" name="actif" value="1"
                           style="width: auto; margin: 0;"
                           <?= (isset($parking['actif']) && $parking['actif']) ? 'checked' : '' ?>>
                    <label for="actif" class="form-label" style="margin: 0; cursor: pointer;">
                        Parking actif
                    </label>
                </div>
            </div>

            <!-- Barre d'occupation visuelle -->
            <?php if (isset($parking['places_totales']) && isset($parking['places_disponibles'])): ?>
                <?php 
                $placesOccupees = $parking['places_totales'] - $parking['places_disponibles'];
                $tauxOccupation = ($placesOccupees / $parking['places_totales']) * 100;
                ?>
                <div class="form-group">
                    <label class="form-label">Taux d'occupation actuel</label>
                    <div class="availability-card" style="margin: 0;">
                        <div class="availability-info">
                            <div class="availability-icon">🚗</div>
                            <div class="availability-text">
                                <span class="places-count"><?= $parking['places_disponibles'] ?> / <?= $parking['places_totales'] ?></span>
                                <span class="occupation-rate">Places disponibles</span>
                            </div>
                        </div>
                        <div class="availability-indicator <?= $tauxOccupation > 80 ? 'warning' : 'success' ?>"></div>
                    </div>
                    <div class="occupation-bar">
                        <div class="occupation-fill" style="width: <?= $tauxOccupation ?>%"></div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="reservation-actions">
                <button type="submit" class="btn btn-primary">
                    <span>💾</span>
                    Mettre à jour
                </button>
                <a href="/?page=parkings_list" class="btn btn-secondary">
                    <span>↩️</span>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Validation côté client avec animations
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const placesTotales = document.getElementById('places_totales');
    const placesDisponibles = document.getElementById('places_disponibles');
    const occupationBar = document.querySelector('.occupation-fill');
    const placesCount = document.querySelector('.places-count');
    const availabilityIndicator = document.querySelector('.availability-indicator');
    
    // Mettre à jour la barre d'occupation en temps réel
    function updateOccupationBar() {
        const total = parseInt(placesTotales.value) || 0;
        const disponibles = parseInt(placesDisponibles.value) || 0;
        const occupees = total - disponibles;
        const taux = total > 0 ? (occupees / total) * 100 : 0;
        
        if (occupationBar) {
            occupationBar.style.width = taux + '%';
        }
        
        if (placesCount) {
            placesCount.textContent = disponibles + ' / ' + total;
        }
        
        if (availabilityIndicator) {
            availabilityIndicator.className = 'availability-indicator ' + (taux > 80 ? 'warning' : 'success');
        }
    }
    
    // Vérifier que les places disponibles ne dépassent pas les places totales
    function validatePlaces() {
        const total = parseInt(placesTotales.value) || 0;
        const disponibles = parseInt(placesDisponibles.value) || 0;
        
        if (disponibles > total) {
            placesDisponibles.setCustomValidity('Les places disponibles ne peuvent pas dépasser les places totales');
            placesDisponibles.style.borderColor = 'var(--danger-color)';
            placesDisponibles.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
        } else {
            placesDisponibles.setCustomValidity('');
            placesDisponibles.style.borderColor = '';
            placesDisponibles.style.boxShadow = '';
        }
        
        updateOccupationBar();
    }
    
    // Ajouter des animations aux inputs
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
    
    placesTotales.addEventListener('input', validatePlaces);
    placesDisponibles.addEventListener('input', validatePlaces);
    
    form.addEventListener('submit', function(e) {
        validatePlaces();
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            
            // Animation d'erreur
            form.style.animation = 'none';
            setTimeout(() => {
                form.style.animation = 'pulse 0.5s ease-in-out';
            }, 10);
        } else {
            // Animation de succès
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<span>⏳</span> Mise à jour...';
            submitBtn.disabled = true;
        }
    });
    
    // Animation d'entrée pour les éléments
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeIn 0.6s ease-out';
                observer.unobserve(entry.target);
            }
        });
    });
    
    document.querySelectorAll('.form-group').forEach(group => {
        observer.observe(group);
    });
    
    // Initialiser la barre d'occupation
    updateOccupationBar();
});
</script>

<style>
/* Styles spécifiques pour cette page */
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
        gap: var(--spacing-md) !important;
    }
    
    .availability-card {
        flex-direction: column;
        text-align: center;
    }
}

/* Animation personnalisée pour les erreurs */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.form-input:invalid {
    animation: shake 0.5s ease-in-out;
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
</style>

<?php require_once '../app/views/templates/footer.php'; ?>