// public/js/modules/register.js
import { notify } from './utils/notify.js'; // Ensure this is the correct path and export
import { notify } from './utils/notify.js';


// Example usage of notify
notify('success', 'Inscription réussie');

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

  validateField(field) {
    const name = field.name;
    const value = field.value.trim();

    delete this.errors[name];
    this.clearFieldError(field);

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
        } else if (value.length < REGISTER_CONFIG.minPasswordLength || value.length > REGISTER_CONFIG.maxPasswordLength) {
          this.errors[name] = ERROR_MESSAGES.password;
        }
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

    if (this.errors[name]) {
      this.showFieldError(field, this.errors[name]);
      return false;
    }

    this.showFieldSuccess(field);
    return true;
  }

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

  showFieldError(field, message) {
    field.classList.add('error');
    field.classList.remove('success');

    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) existingError.remove();

    const errorElement = document.createElement('div');
    errorElement.className = 'field-error';
    errorElement.textContent = message;
    field.parentNode.appendChild(errorElement);
  }

  showFieldSuccess(field) {
    field.classList.add('success');
    field.classList.remove('error');
    this.clearFieldError(field);
  }

  clearFieldError(field) {
    const errorElement = field.parentNode.querySelector('.field-error');
    if (errorElement) errorElement.remove();
  }

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
 * Gestion du formulaire d'inscription
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

  init() {
    const inputs = this.form.querySelectorAll('input[required]');
    inputs.forEach(input => {
      input.addEventListener('blur', () => this.validator.validateField(input));
      input.addEventListener('input', () => {
        if (input.classList.contains('error')) {
          this.validator.clearFieldError(input);
          input.classList.remove('error');
        }
      });
    });

    const phoneInput = this.form.querySelector('[name="phone"]');
    if (phoneInput) {
      phoneInput.addEventListener('input', this.formatPhone.bind(this));
    }

    const passwordInput = this.form.querySelector('[name="password"]');
    if (passwordInput) {
      passwordInput.addEventListener('input', this.updatePasswordStrength.bind(this));
    }

    this.form.addEventListener('submit', this.handleSubmit.bind(this));

    console.log('✅ RegisterFormHandler initialisé');
  }

  formatPhone(event) {
    let value = event.target.value.replace(/\D/g, '');
    if (value.startsWith('33')) {
      value = '+' + value;
    } else if (value.startsWith('0')) {
      value = value.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
    }
    event.target.value = value;
  }

  updatePasswordStrength(event) {
    const password = event.target.value;
    const strengthIndicator = document.getElementById('password-strength');
    if (!strengthIndicator) return;

    let strength = 0;
    let feedback = [];

    if (password.length >= 8) strength++; else feedback.push('au moins 8 caractères');
    if (/[a-z]/.test(password)) strength++; else feedback.push('une minuscule');
    if (/[A-Z]/.test(password)) strength++; else feedback.push('une majuscule');
    if (/\d/.test(password)) strength++; else feedback.push('un chiffre');
    if (/[^a-zA-Z0-9]/.test(password)) strength++; else feedback.push('un caractère spécial');

    const classes = ['very-weak', 'weak', 'fair', 'good', 'strong'];
    const labels = ['Très faible', 'Faible', 'Moyen', 'Bon', 'Fort'];

    strengthIndicator.className = `password-strength ${classes[strength - 1] || 'very-weak'}`;
    strengthIndicator.textContent = `Force: ${labels[strength - 1] || 'Très faible'}`;

    if (feedback.length > 0 && password.length > 0) {
      strengthIndicator.textContent += ` (Manque: ${feedback.join(', ')})`;
    }
  }

  async handleSubmit(event) {
    event.preventDefault();
    if (this.isSubmitting) return;

    if (!this.validator.validateForm()) {
      notify('error', 'Veuillez corriger les erreurs du formulaire');
      return;
    }

    this.isSubmitting = true;
    const submitButton = this.form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;

    try {
      submitButton.disabled = true;
      submitButton.textContent = '⏳ Inscription en cours...';

      const formData = new FormData(this.form);

      const response = await fetch(this.form.action || '/?page=register', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        body: formData
      });

      await this.handleResponse(response);

    } catch (err) {
      console.error('❌ Erreur réseau :', err);
      notify('error', ERROR_MESSAGES.networkError);
    } finally {
      this.isSubmitting = false;
      submitButton.disabled = false;
      submitButton.textContent = originalText;
    }
  }

  async handleResponse(response) {
    const contentType = response.headers.get('content-type');
    if (contentType && contentType.includes('application/json')) {
      const result = await response.json();

      if (result.success) {
        notify('success', result.message || 'Inscription réussie !');
        setTimeout(() => {
          window.location.href = result.redirect || '/?page=login';
        }, 1500);
      } else {
        if (result.errors) {
          Object.entries(result.errors).forEach(([field, message]) => {
            const el = this.form.querySelector(`[name="${field}"]`);
            if (el) this.validator.showFieldError(el, message);
          });
          notify('error', 'Veuillez corriger les erreurs signalées');
        } else {
          notify('error', result.message || ERROR_MESSAGES.serverError);
        }
      }
    } else {
      const html = await response.text();
      console.warn('Réponse HTML brute reçue (non JSON) :', html.slice(0, 200));
      notify('error', ERROR_MESSAGES.serverError);
    }
  }
}

/**
 * Utilitaires liés à l'inscription
 */
const RegisterUtils = {
  async checkEmailAvailability(email) {
    try {
      const res = await fetch('/?page=check_email', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: `email=${encodeURIComponent(email)}`
      });

      if (res.ok) {
        const json = await res.json();
        return json.available;
      }
    } catch (err) {
      console.warn('Erreur réseau pour check_email:', err);
    }
    return true;
  },

  generateSecurePassword(length = 12) {
    const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    return Array.from({ length }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
  },

  togglePasswordVisibility(button) {
    const input = button.parentNode.querySelector('input[type="password"], input[type="text"]');
    if (input.type === 'password') {
      input.type = 'text';
      button.textContent = '🙈';
    } else {
      input.type = 'password';
      button.textContent = '👁️';
    }
  }
};

// Initialisation auto
document.addEventListener('DOMContentLoaded', () => {
  new RegisterFormHandler();
  console.log('🚀 Module register.js chargé');
});

document.addEventListener('click', (event) => {
  if (event.target.classList.contains('password-toggle')) {
    event.preventDefault();
    RegisterUtils.togglePasswordVisibility(event.target);
  }
});

// Export modulaire
export { RegisterFormHandler, FormValidator, RegisterUtils };
