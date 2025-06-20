/**
 * Module de gestion de la connexion utilisateur
 * Gère l'authentification et la redirection après connexion
 */

import { success, error, warning, info } from './utils/notify.js';

/**
 * Fonction pour effectuer une requête POST
 * @param {string} url - URL de destination
 * @param {Object} data - Données à envoyer
 * @returns {Promise<Object>} - Réponse du serveur
 */
async function post(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        return await response.json();
    } catch (err) {
        console.error('Erreur lors de la requête POST:', err);
        throw err;
    }
}

/**
 * Valide les données du formulaire de connexion
 * @param {Object} data - Données du formulaire
 * @returns {Object} - Objet contenant la validité et les erreurs
 */
function validateLoginData(data) {
    const errors = [];
    
    if (!data.email || data.email.trim() === '') {
        errors.push('L\'email est requis');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
        errors.push('Format d\'email invalide');
    }
    
    if (!data.password || data.password.trim() === '') {
        errors.push('Le mot de passe est requis');
    } else if (data.password.length < 6) {
        errors.push('Le mot de passe doit contenir au moins 6 caractères');
    }
    
    return {
        isValid: errors.length === 0,
        errors: errors
    };
}

/**
 * Gère l'affichage des états de chargement
 * @param {HTMLFormElement} form - Formulaire
 * @param {boolean} isLoading - État de chargement
 */
function toggleLoadingState(form, isLoading) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const inputs = form.querySelectorAll('input');
    
    if (isLoading) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Connexion...';
        inputs.forEach(input => input.disabled = true);
    } else {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Se connecter';
        inputs.forEach(input => input.disabled = false);
    }
}

/**
 * Efface les messages d'erreur précédents
 * @param {HTMLFormElement} form - Formulaire
 */
function clearErrorMessages(form) {
    const errorElements = form.querySelectorAll('.error-message');
    errorElements.forEach(element => element.remove());
}

/**
 * Affiche les erreurs de validation dans le formulaire
 * @param {HTMLFormElement} form - Formulaire
 * @param {Array} errors - Liste des erreurs
 */
function displayFormErrors(form, errors) {
    clearErrorMessages(form);
    
    errors.forEach(errorMsg => {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.color = '#dc3545';
        errorDiv.style.fontSize = '14px';
        errorDiv.style.marginTop = '5px';
        errorDiv.textContent = errorMsg;
        
        form.appendChild(errorDiv);
    });
}

/**
 * Gère la soumission du formulaire de connexion
 * @param {Event} e - Événement de soumission
 * @param {HTMLFormElement} form - Formulaire
 */
async function handleLoginSubmit(e, form) {
    e.preventDefault();
    
    // Récupération des données
    const data = {
        email: form.email.value.trim(),
        password: form.password.value
    };
    
    // Validation côté client
    const validation = validateLoginData(data);
    if (!validation.isValid) {
        displayFormErrors(form, validation.errors);
        error('Veuillez corriger les erreurs dans le formulaire');
        return;
    }
    
    // Effacer les erreurs précédentes
    clearErrorMessages(form);
    
    // État de chargement
    toggleLoadingState(form, true);
    
    try {
        info('Connexion en cours...', 'Authentification', { duration: 2000 });
        
        const result = await post('/app/controllers/LoginController.php', data);
        
        if (result.success) {
            success('Connexion réussie ! Redirection...', 'Bienvenue');
            
            // Sauvegarde des informations utilisateur si nécessaire
            if (result.user) {
                localStorage.setItem('user', JSON.stringify(result.user));
            }
            
            // Redirection après un court délai pour laisser voir la notification
            setTimeout(() => {
                window.location.href = result.redirect || '/dashboard';
            }, 1500);
            
        } else {
            error(result.message || 'Email ou mot de passe incorrect', 'Échec de connexion');
            
            // Focus sur le champ email pour faciliter la correction
            form.email.focus();
        }
        
    } catch (err) {
        console.error('Erreur lors de la connexion:', err);
        error('Erreur de connexion au serveur. Veuillez réessayer.', 'Erreur réseau');
    } finally {
        // Rétablir l'état normal du formulaire
        toggleLoadingState(form, false);
    }
}

/**
 * Gère la fonctionnalité "Se souvenir de moi"
 * @param {HTMLFormElement} form - Formulaire
 */
function handleRememberMe(form) {
    const rememberCheckbox = form.querySelector('#remember-me');
    const emailInput = form.email;
    
    if (!rememberCheckbox || !emailInput) return;
    
    // Charger l'email sauvegardé au chargement de la page
    const savedEmail = localStorage.getItem('remembered_email');
    if (savedEmail) {
        emailInput.value = savedEmail;
        rememberCheckbox.checked = true;
    }
    
    // Sauvegarder/supprimer l'email selon la case cochée
    form.addEventListener('submit', () => {
        if (rememberCheckbox.checked) {
            localStorage.setItem('remembered_email', emailInput.value.trim());
        } else {
            localStorage.removeItem('remembered_email');
        }
    });
}

/**
 * Ajoute la fonctionnalité de basculement de visibilité du mot de passe
 * @param {HTMLFormElement} form - Formulaire
 */
function initPasswordToggle(form) {
    const passwordInput = form.password;
    const toggleBtn = form.querySelector('.password-toggle');
    
    if (!passwordInput || !toggleBtn) return;
    
    toggleBtn.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        toggleBtn.textContent = isPassword ? '🙈' : '👁️';
        toggleBtn.setAttribute('aria-label', isPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
    });
}

/**
 * Initialise le module de connexion
 * Configure tous les événements et fonctionnalités
 */
export function initLogin() {
    const form = document.querySelector('#login-form');
    if (!form) {
        console.warn('Formulaire de connexion non trouvé');
        return;
    }
    
    // Vérification des champs requis
    if (!form.email || !form.password) {
        console.error('Champs email ou password manquants dans le formulaire');
        error('Configuration du formulaire incorrecte', 'Erreur');
        return;
    }
    
    // Gestion de la soumission du formulaire
    form.addEventListener('submit', (e) => handleLoginSubmit(e, form));
    
    // Fonctionnalité "Se souvenir de moi"
    handleRememberMe(form);
    
    // Basculement de visibilité du mot de passe
    initPasswordToggle(form);
    
    // Focus automatique sur le premier champ
    form.email.focus();
    
    // Nettoyage automatique des erreurs lors de la saisie
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            clearErrorMessages(form);
        });
    });
    
    console.log('Module de connexion initialisé avec succès');
}

/**
 * Fonction de déconnexion
 * @param {string} redirectUrl - URL de redirection après déconnexion
 */
export async function logout(redirectUrl = '/login') {
    try {
        info('Déconnexion en cours...', 'Au revoir');
        
        // Appel au serveur pour déconnexion
        await post('/app/controllers/LogoutController.php', {});
        
        // Nettoyage du stockage local
        localStorage.removeItem('user');
        sessionStorage.clear();
        
        success('Déconnexion réussie', 'À bientôt');
        
        // Redirection
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 1500);
        
    } catch (err) {
        console.error('Erreur lors de la déconnexion:', err);
        warning('Erreur lors de la déconnexion, redirection forcée');
        
        // Forcer la redirection même en cas d'erreur
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 2000);
    }
}

/**
 * Vérifie si l'utilisateur est connecté
 * @returns {boolean} - True si connecté
 */
export function isLoggedIn() {
    const user = localStorage.getItem('user');
    return user !== null && user !== 'undefined';
}

/**
 * Redirige vers la page de connexion si non connecté
 * @param {string} loginUrl - URL de la page de connexion
 */
export function requireAuth(loginUrl = '/login') {
    if (!isLoggedIn()) {
        warning('Vous devez être connecté pour accéder à cette page');
        setTimeout(() => {
            window.location.href = loginUrl;
        }, 2000);
        return false;
    }
    return true;
}