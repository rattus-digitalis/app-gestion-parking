<?php
$title = "Créer un utilisateur";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

// Message flash
if (isset($_SESSION['flash_message'])): ?>
    <div class="flash-message <?= htmlspecialchars($_SESSION['flash_type'] ?? 'info') ?>" role="alert">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
    </div>
<?php
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
endif;

// Messages d'erreur ou de succès via GET
if (isset($_GET['error'])): ?>
    <div class="alert alert-danger" role="alert">
        ❌ <?= htmlspecialchars($_GET['error']) ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success" role="alert">
        ✅ <?= htmlspecialchars($_GET['success']) ?>
    </div>
<?php endif; ?>

<main class="container create-user" role="main">
    <header>
        <h1>👤 Créer un utilisateur</h1>
        <p>Ajoutez un nouveau membre à la plateforme Parkly</p>
    </header>

    <nav class="breadcrumb">
        <a href="/?page=dashboard_admin">Tableau de bord</a> > 
        <a href="/?page=admin_users">Gestion des utilisateurs</a> > 
        <span>Créer un utilisateur</span>
    </nav>

    <form id="createUserForm" method="POST" action="/?page=create_user" class="form-card">
        <div class="form-grid">
            <div class="form-group">
                <label for="last_name" class="required">Nom</label>
                <input type="text" 
                       id="last_name" 
                       name="last_name" 
                       required 
                       placeholder="Dupont"
                       autocomplete="family-name"
                       value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="first_name" class="required">Prénom</label>
                <input type="text" 
                       id="first_name" 
                       name="first_name" 
                       required 
                       placeholder="Jean"
                       autocomplete="given-name"
                       value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="required">Adresse email</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   required 
                   placeholder="jean.dupont@exemple.com"
                   autocomplete="email"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="phone" class="required">Numéro de téléphone</label>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   required 
                   placeholder="06 12 34 56 78"
                   autocomplete="tel"
                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="role" class="required">Rôle</label>
            <select id="role" name="role" required>
                <option value="user" <?= ($_POST['role'] ?? '') === 'user' ? 'selected' : '' ?>>
                    Utilisateur
                </option>
                <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>
                    Administrateur
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="password" class="required">Mot de passe</label>
            <div class="password-wrapper">
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       placeholder="Minimum 8 caractères"
                       minlength="8"
                       autocomplete="new-password">
                <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Afficher/Masquer le mot de passe">
                    👁️
                </button>
            </div>
            <small class="form-help">Le mot de passe doit contenir au moins 8 caractères</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" id="submitBtn">
                ✨ Créer l'utilisateur
            </button>
            <a href="/?page=admin_users" class="btn btn-secondary">
                Annuler
            </a>
        </div>
    </form>
</main>

<style>
/* Styles spécifiques pour le formulaire de création */
.create-user {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.form-card {
    background: var(--bg-secondary, #ffffff);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border-color, #e2e8f0);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary, #2d3748);
}

.form-group label.required::after {
    content: ' *';
    color: #e53e3e;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border-color, #e2e8f0);
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
    background: var(--bg-primary, #ffffff);
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary-color, #667eea);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.password-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
    color: #718096;
    padding: 0;
    width: auto;
}

.password-toggle:hover {
    color: #2d3748;
}

.form-help {
    display: block;
    margin-top: 4px;
    font-size: 14px;
    color: #718096;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-start;
    margin-top: 30px;
}

.breadcrumb {
    margin-bottom: 20px;
    font-size: 14px;
}

.breadcrumb a {
    color: var(--primary-color, #667eea);
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb > span {
    color: #718096;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .create-user {
        padding: 10px;
    }
    
    .form-card {
        padding: 20px;
    }
}
</style>

<script>
// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.querySelector('.password-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.textContent = '🙈';
        toggleBtn.setAttribute('aria-label', 'Masquer le mot de passe');
    } else {
        passwordInput.type = 'password';
        toggleBtn.textContent = '👁️';
        toggleBtn.setAttribute('aria-label', 'Afficher le mot de passe');
    }
}

// Form submission with AJAX
document.getElementById('createUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Création en cours...';
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('/?page=create_user', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: formData
        });
        
        const contentType = response.headers.get('content-type');
        
        if (contentType && contentType.includes('application/json')) {
            const result = await response.json();
            
            if (result.success) {
                // Success
                showNotification(result.message || 'Utilisateur créé avec succès', 'success');
                
                // Reset form
                this.reset();
                
                // Redirect after 2 seconds
                setTimeout(() => {
                    window.location.href = '/?page=admin_users';
                }, 2000);
            } else {
                throw new Error(result.error || 'Erreur lors de la création');
            }
        } else {
            // HTML response - check if it's a success
            if (response.ok || response.status === 302) {
                showNotification('Utilisateur créé avec succès', 'success');
                setTimeout(() => {
                    window.location.href = '/?page=admin_users';
                }, 1500);
            } else {
                throw new Error('Erreur du serveur');
            }
        }
        
    } catch (error) {
        console.error('Erreur:', error);
        showNotification(error.message, 'error');
        
        // Restore button
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});

// Notification function (utilise votre système existant)
function showNotification(message, type) {
    // Utiliser votre système de notifications avancé
    import('./js/modules/utils/notify.js').then(notifyModule => {
        if (type === 'success') {
            notifyModule.success(message);
        } else {
            notifyModule.error(message);
        }
    }).catch(() => {
        // Fallback simple si le module ne charge pas
        const notification = document.createElement('div');
        notification.className = `flash-message ${type}`;
        notification.textContent = message;
        notification.setAttribute('role', 'alert');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: 600;
            color: white;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
        `;
        
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    });
}
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>