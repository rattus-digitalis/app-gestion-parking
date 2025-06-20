// public/js/modules/register.js

import { success, error, info, warning } from './utils/notify.js';

// Fonction notify locale pour compatibilité
function notify(message, type = 'info') {
    switch (type.toLowerCase()) {
        case 'success':
            return success(message);
        case 'error':
            return error(message);
        case 'warning':
            return warning(message);
        case 'info':
        default:
            return info(message);
    }
}

/**
 * Configuration du module de registration
 */
const REGISTER_CONFIG = {
    minPasswordLength: 8,
    maxPasswordLength: 128,
    emailRegex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    phoneRegex: /^(\+33|0)[1-9](\d{8})$/,
    nameRegex: /^[a-zA-ZÀ-ÿ\s'-]{2,50}$/
};

/**
 * Messages d'erreur
 */
const ERROR_MESSAGES = {
    required: 'Ce champ est obligatoire',
    email: 'Veuillez entrer une adresse email valide',
    phone: 'Veuillez entrer un numéro de téléphone français valide (ex: 06 12 34 56 78)',
    password: `Le mot de passe doit contenir entre ${REGISTER_CONFIG.minPasswordLength} et ${REGISTER_CONFIG.maxPasswordLength} caractères`,
    passwordConfirm: 'La confirmation du mot de passe ne correspond pas',
    name: 'Le nom doit contenir entre 2 et 50 caractères (lettres, espaces, apostrophes et tirets uniquement)',
    serverError: 'Erreur du serveur. Veuillez réessayer.',
    networkError: 'Erreur de connexion. Vérifiez votre connexion internet.'
};

/**
 * Validation côté client
 */
class FormValidator {
    constructor(form) {
        this.form = form;
        this.errors = {};
    }

    /**
     * Valide un champ spécifique
     */
    validateField(field) {
        const name = field.name;
        const value = field.value.trim();
        
        // Réinitialiser l'erreur pour ce champ
        delete this.errors[name];
        this.clearFieldError(field);

        // Validation par type de champ
        switch (name) {
            case 'last_name':
            case 'first_name':
                if (!value) {
                    this.errors[name] = ERROR_MESSAGES.required;
                } else if (!REGISTER_CONFIG.nameRegex.test(value)) {
                    this.errors[name] = ERROR_MESSAGES.name;
                }
                break;

            case 'email':
                if (!value) {
                    this.errors[name] = ERROR_MESSAGES.required;
                } else if (!REGISTER_CONFIG.emailRegex.test(value)) {
                    this.errors[name] = ERROR_MESSAGES.email;
                }
                break;

            case 'phone':
                if (!value) {
                    this.errors[name] = ERROR_MESSAGES.required;
                } else if (!REGISTER_CONFIG.phoneRegex.test(value.replace(/\s/g, ''))) {
                    this.errors[name] = ERROR_MESSAGES.phone;
                }
                break;

            case 'password':
                if (!value) {
                    this.errors[name] = ERROR_MESSAGES.required;
                } else if (value.length < REGISTER_CONFIG.minPasswordLength || 
                          value.length > REGISTER_CONFIG.maxPasswordLength) {
                    this.errors[name] = ERROR_MESSAGES.password;
                }
                // Revalider la confirmation si elle existe
                const confirmField = this.form.querySelector('[name="password_confirm"]');
                if (confirmField && confirmField.value) {
                    this.validateField(confirmField);
                }
                break;

            case 'password_confirm':
                const passwordField = this.form.querySelector('[name="password"]');
                if (!value) {
                    this.errors[name] = ERROR_MESSAGES.required;
                } else if (value !== passwordField.value) {
                    this.errors[name] = ERROR_MESSAGES.passwordConfirm;
                }
                break;
        }

        // Afficher l'erreur si elle existe
        if (this.errors[name]) {
            this.showFieldError(field, this.errors[name]);
            return false;
        }

        this.showFieldSuccess(field);
        return true;
    }

    /**
     * Valide tout le formulaire
     */
    validateForm() {
        const fields = this.form.querySelectorAll('input[required]');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * Affiche une erreur sur un champ
     */
    showFieldError(field, message) {
        field.classList.add('error');
        field.classList.remove('success');
        
        // Supprimer l'ancien message d'erreur
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }

        // Ajouter le nouveau message d'erreur
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        field.parentNode.appendChild(errorElement);
    }

    /**
     * Affiche le succès sur un champ
     */
    showFieldSuccess(field) {
        field.classList.add('success');
        field.classList.remove('error');
        this.clearFieldError(field);
    }

    /**
     * Efface l'erreur d'un champ
     */
    clearFieldError(field) {
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    /**
     * Efface toutes les erreurs
     */
    clearAllErrors() {
        this.errors = {};
        const errorElements = this.form.querySelectorAll('.field-error');
        errorElements.forEach(el => el.remove());
        
        const fields = this.form.querySelectorAll('input');
        fields.forEach(field => {
            field.classList.remove('error', 'success');
        });
    }
}

/**
 * Gestionnaire du formulaire d'inscription
 */
class RegisterFormHandler {
    constructor() {
        this.form = document.getElementById('registerForm');
        this.validator = null;
        this.isSubmitting = false;

        if (this.form) {
            this.validator = new FormValidator(this.form);
            this.init();
        }
    }

    /**
     * Initialise les événements
     */
    init() {
        // Validation en temps réel
        const inputs = this.form.querySelectorAll('input[required]');
        inputs.forEach(input => {
            // Validation à la perte de focus
            input.addEventListener('blur', () => {
                this.validator.validateField(input);
            });

            // Effacer l'erreur lors de la saisie
            input.addEventListener('input', () => {
                if (input.classList.contains('error')) {
                    this.validator.clearFieldError(input);
                    input.classList.remove('error');
                }
            });
        });

        // Formatage automatique du téléphone
        const phoneInput = this.form.querySelector('[name="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', this.formatPhone.bind(this));
        }

        // Indicateur de force du mot de passe
        const passwordInput = this.form.querySelector('[name="password"]');
        if (passwordInput) {
            passwordInput.addEventListener('input', this.updatePasswordStrength.bind(this));
        }

        // Soumission du formulaire
        this.form.addEventListener('submit', this.handleSubmit.bind(this));

        console.log('✅ RegisterFormHandler initialisé');
    }

    /**
     * Formate automatiquement le numéro de téléphone
     */
    formatPhone(event) {
        let value = event.target.value.replace(/\D/g, '');
        
        if (value.startsWith('33')) {
            value = '+' + value;
        } else if (value.startsWith('0')) {
            // Format français avec espaces
            value = value.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
        }
        
        event.target.value = value;
    }

    /**
     * Met à jour l'indicateur de force du mot de passe
     */
    updatePasswordStrength(event) {
        const password = event.target.value;
        const strengthIndicator = document.getElementById('password-strength');
        
        if (!strengthIndicator) return;

        let strength = 0;
        let feedback = [];

        // Longueur
        if (password.length >= 8) strength++;
        else feedback.push('au moins 8 caractères');

        // Minuscules
        if (/[a-z]/.test(password)) strength++;
        else feedback.push('une minuscule');

        // Majuscules
        if (/[A-Z]/.test(password)) strength++;
        else feedback.push('une majuscule');

        // Chiffres
        if (/\d/.test(password)) strength++;
        else feedback.push('un chiffre');

        // Caractères spéciaux
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        else feedback.push('un caractère spécial');

        // Affichage
        const strengthClasses = ['very-weak', 'weak', 'fair', 'good', 'strong'];
        const strengthTexts = ['Très faible', 'Faible', 'Moyen', 'Bon', 'Fort'];
        
        strengthIndicator.className = `password-strength ${strengthClasses[strength - 1] || 'very-weak'}`;
        strengthIndicator.textContent = `Force: ${strengthTexts[strength - 1] || 'Très faible'}`;
        
        if (feedback.length > 0 && password.length > 0) {
            strengthIndicator.textContent += ` (Manque: ${feedback.join(', ')})`;
        }
    }

    /**
     * Gère la soumission du formulaire
     */
    async handleSubmit(event) {
        event.preventDefault();

        if (this.isSubmitting) {
            return;
        }

        console.log('📝 Soumission du formulaire d\'inscription');

        // Validation complète
        if (!this.validator.validateForm()) {
            error('Veuillez corriger les erreurs du formulaire');
            return;
        }

        this.isSubmitting = true;
        const submitButton = this.form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        try {
            // Désactiver le bouton et afficher le loading
            submitButton.disabled = true;
            submitButton.textContent = '⏳ Inscription en cours...';

            // Préparer les données
            const formData = new FormData(this.form);
            
            // Log des données (sans le mot de passe pour la sécurité)
            const dataForLog = {};
            for (let [key, value] of formData.entries()) {
                dataForLog[key] = key.includes('password') ? '[HIDDEN]' : value;
            }
            console.log('📤 Données à envoyer:', dataForLog);

            // Envoyer la requête
            const response = await fetch(this.form.action || '/?page=register', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: formData
            });

            console.log('📡 Réponse reçue:', response.status, response.statusText);

            // Traitement de la réponse
            await this.handleResponse(response);

        } catch (err) {
            console.error('❌ Erreur lors de l\'inscription:', err);
            error(ERROR_MESSAGES.networkError);
        } finally {
            // Restaurer le bouton
            this.isSubmitting = false;
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }

    /**
     * Traite la réponse du serveur
     */
    async handleResponse(response) {
        const contentType = response.headers.get('content-type');

        if (contentType && contentType.includes('application/json')) {
            // Réponse JSON
            const result = await response.json();
            console.log('📋 Réponse JSON:', result);

            if (result.success) {
                success(result.message || 'Inscription réussie !');
                
                // Redirection après succès
                setTimeout(() => {
                    window.location.href = result.redirect || '/?page=login';
                }, 1500);
            } else {
                // Erreurs de validation du serveur
                if (result.errors && typeof result.errors === 'object') {
                    Object.entries(result.errors).forEach(([field, message]) => {
                        const fieldElement = this.form.querySelector(`[name="${field}"]`);
                        if (fieldElement) {
                            this.validator.showFieldError(fieldElement, message);
                        }
                    });
                    error('Veuillez corriger les erreurs signalées');
                } else {
                    error(result.message || 'Erreur lors de l\'inscription');
                }
            }
        } else {
            // Réponse HTML
            const text = await response.text();
            
            if (response.ok || response.status === 302) {
                // Succès probable
                success('Inscription réussie !');
                setTimeout(() => {
                    window.location.href = '/?page=login';
                }, 1500);
            } else {
                console.error('❌ Réponse HTML d\'erreur:', text.substring(0, 200));
                error(ERROR_MESSAGES.serverError);
            }
        }
    }
}

/**
 * Utilitaires pour l'inscription
 */
const RegisterUtils = {
    /**
     * Vérifie la disponibilité d'un email
     */
    async checkEmailAvailability(email) {
        try {
            const response = await fetch('/?page=check_email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: `email=${encodeURIComponent(email)}`
            });

            if (response.ok) {
                const result = await response.json();
                return result.available;
            }
        } catch (err) {
            console.warn('Impossible de vérifier la disponibilité de l\'email:', err);
        }
        return true; // En cas d'erreur, on assume que l'email est disponible
    },

    /**
     * Génère un mot de passe sécurisé
     */
    generateSecurePassword(length = 12) {
        const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        let password = '';
        
        for (let i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        
        return password;
    },

    /**
     * Affiche/masque le mot de passe
     */
    togglePasswordVisibility(button) {
        const input = button.parentNode.querySelector('input[type="password"], input[type="text"]');
        
        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '🙈';
            button.setAttribute('aria-label', 'Masquer le mot de passe');
        } else {
            input.type = 'password';
            button.textContent = '👁️';
            button.setAttribute('aria-label', 'Afficher le mot de passe');
        }
    }
};

// Initialisation automatique quand le DOM est prêt
document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 Initialisation du module register.js');
    new RegisterFormHandler();
});

// Gestion des boutons toggle password
document.addEventListener('click', (event) => {
    if (event.target.classList.contains('password-toggle')) {
        event.preventDefault();
        RegisterUtils.togglePasswordVisibility(event.target);
    }
});

// Export pour utilisation externe
export { RegisterFormHandler, FormValidator, RegisterUtils, notify };