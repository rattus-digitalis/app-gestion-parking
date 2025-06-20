/**
 * Système de notifications pour l'application Parkly
 * Gère l'affichage de messages toast et d'alertes utilisateur
 */

/**
 * Configuration par défaut des notifications
 */
const DEFAULT_CONFIG = {
    duration: 5000, // 5 secondes
    position: 'top-right',
    showClose: true,
    pauseOnHover: true,
    animation: 'slide'
};

/**
 * Types de notifications disponibles
 */
const NOTIFICATION_TYPES = {
    SUCCESS: 'success',
    ERROR: 'error',
    WARNING: 'warning',
    INFO: 'info'
};

/**
 * Conteneur principal des notifications
 */
let notificationContainer = null;

/**
 * Initialise le conteneur de notifications
 */
function initNotificationContainer() {
    if (notificationContainer) return;

    notificationContainer = document.createElement('div');
    notificationContainer.id = 'notification-container';
    notificationContainer.className = 'notification-container';
    
    // Styles CSS intégrés
    const style = document.createElement('style');
    style.textContent = `
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            pointer-events: none;
        }
        
        .notification {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            min-width: 300px;
            max-width: 400px;
            padding: 16px;
            position: relative;
            pointer-events: auto;
            transform: translateX(100%);
            transition: all 0.3s ease;
            border-left: 4px solid #007bff;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.success {
            border-left-color: #28a745;
        }
        
        .notification.error {
            border-left-color: #dc3545;
        }
        
        .notification.warning {
            border-left-color: #ffc107;
        }
        
        .notification.info {
            border-left-color: #17a2b8;
        }
        
        .notification-icon {
            font-size: 20px;
            line-height: 1;
            margin-top: 2px;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-title {
            font-weight: 600;
            margin-bottom: 4px;
            color: #333;
        }
        
        .notification-message {
            color: #666;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            padding: 0;
            position: absolute;
            right: 12px;
            top: 12px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .notification-close:hover {
            color: #666;
        }
        
        .notification-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(0, 0, 0, 0.1);
            transition: width linear;
        }
        
        .notification.success .notification-progress {
            background: #28a745;
        }
        
        .notification.error .notification-progress {
            background: #dc3545;
        }
        
        .notification.warning .notification-progress {
            background: #ffc107;
        }
        
        .notification.info .notification-progress {
            background: #17a2b8;
        }
        
        @media (max-width: 480px) {
            .notification-container {
                top: 10px;
                right: 10px;
                left: 10px;
            }
            
            .notification {
                min-width: auto;
                max-width: none;
            }
        }
    `;
    
    document.head.appendChild(style);
    document.body.appendChild(notificationContainer);
}

/**
 * Crée une notification
 * @param {string} type - Type de notification (success, error, warning, info)
 * @param {string} title - Titre de la notification
 * @param {string} message - Message de la notification
 * @param {Object} options - Options personnalisées
 * @returns {HTMLElement} - Élément de notification créé
 */
function createNotification(type, title, message, options = {}) {
    const config = { ...DEFAULT_CONFIG, ...options };
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    // Icônes selon le type
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    };
    
    notification.innerHTML = `
        <div class="notification-icon">${icons[type] || icons.info}</div>
        <div class="notification-content">
            <div class="notification-title">${title}</div>
            <div class="notification-message">${message}</div>
        </div>
        ${config.showClose ? '<button class="notification-close">&times;</button>' : ''}
        ${config.duration > 0 ? '<div class="notification-progress"></div>' : ''}
    `;
    
    return notification;
}

/**
 * Affiche une notification
 * @param {string} type - Type de notification
 * @param {string} title - Titre
 * @param {string} message - Message
 * @param {Object} options - Options
 */
function showNotification(type, title, message, options = {}) {
    initNotificationContainer();
    
    const config = { ...DEFAULT_CONFIG, ...options };
    const notification = createNotification(type, title, message, config);
    
    // Gestion de la fermeture
    const closeBtn = notification.querySelector('.notification-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => hideNotification(notification));
    }
    
    // Pause au survol
    if (config.pauseOnHover && config.duration > 0) {
        let timeoutId;
        let progressBar = notification.querySelector('.notification-progress');
        let startTime = Date.now();
        let remainingTime = config.duration;
        
        const startProgress = () => {
            if (progressBar) {
                progressBar.style.width = '100%';
                progressBar.style.transition = `width ${remainingTime}ms linear`;
                setTimeout(() => {
                    if (progressBar) progressBar.style.width = '0%';
                }, 10);
            }
            
            timeoutId = setTimeout(() => hideNotification(notification), remainingTime);
        };
        
        const pauseProgress = () => {
            clearTimeout(timeoutId);
            remainingTime = remainingTime - (Date.now() - startTime);
            if (progressBar) {
                progressBar.style.transition = 'none';
            }
        };
        
        const resumeProgress = () => {
            startTime = Date.now();
            startProgress();
        };
        
        notification.addEventListener('mouseenter', pauseProgress);
        notification.addEventListener('mouseleave', resumeProgress);
        
        startProgress();
    } else if (config.duration > 0) {
        // Auto-dismiss sans pause
        const progressBar = notification.querySelector('.notification-progress');
        if (progressBar) {
            progressBar.style.width = '100%';
            progressBar.style.transition = `width ${config.duration}ms linear`;
            setTimeout(() => {
                progressBar.style.width = '0%';
            }, 10);
        }
        
        setTimeout(() => hideNotification(notification), config.duration);
    }
    
    // Ajout au conteneur
    notificationContainer.appendChild(notification);
    
    // Animation d'entrée
    requestAnimationFrame(() => {
        notification.classList.add('show');
    });
    
    return notification;
}

/**
 * Cache une notification
 * @param {HTMLElement} notification - Élément à cacher
 */
function hideNotification(notification) {
    notification.classList.remove('show');
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

/**
 * Notification de succès
 * @param {string} message - Message à afficher
 * @param {string} title - Titre (optionnel)
 * @param {Object} options - Options personnalisées
 */
export function success(message, title = 'Succès', options = {}) {
    return showNotification(NOTIFICATION_TYPES.SUCCESS, title, message, options);
}

/**
 * Notification d'erreur
 * @param {string} message - Message à afficher
 * @param {string} title - Titre (optionnel)
 * @param {Object} options - Options personnalisées
 */
export function error(message, title = 'Erreur', options = {}) {
    return showNotification(NOTIFICATION_TYPES.ERROR, title, message, {
        duration: 8000, // Plus long pour les erreurs
        ...options
    });
}

/**
 * Notification d'avertissement
 * @param {string} message - Message à afficher
 * @param {string} title - Titre (optionnel)
 * @param {Object} options - Options personnalisées
 */
export function warning(message, title = 'Attention', options = {}) {
    return showNotification(NOTIFICATION_TYPES.WARNING, title, message, options);
}

/**
 * Notification d'information
 * @param {string} message - Message à afficher
 * @param {string} title - Titre (optionnel)
 * @param {Object} options - Options personnalisées
 */
export function info(message, title = 'Information', options = {}) {
    return showNotification(NOTIFICATION_TYPES.INFO, title, message, options);
}

/**
 * Notification personnalisée
 * @param {Object} config - Configuration complète
 */
export function custom(config) {
    const { type, title, message, ...options } = config;
    return showNotification(type, title, message, options);
}

/**
 * Efface toutes les notifications
 */
export function clearAll() {
    if (notificationContainer) {
        const notifications = notificationContainer.querySelectorAll('.notification');
        notifications.forEach(hideNotification);
    }
}

/**
 * Notification pour les réponses d'API
 * @param {Object} response - Réponse de l'API
 * @param {string} successMessage - Message de succès personnalisé
 */
export function handleApiResponse(response, successMessage = null) {
    if (response.success) {
        success(successMessage || response.message || 'Opération réussie');
    } else {
        error(response.message || 'Une erreur est survenue');
    }
}

/**
 * Fonction notify simple pour compatibilité (système flash message)
 * @param {string} type - Type de notification
 * @param {string} message - Message à afficher
 */
export function notify(type, message) {
    // Utiliser le nouveau système au lieu du flash-message
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
 * Export de compatibilité pour l'ancien code
 * @param {string} message - Message à afficher  
 * @param {string} type - Type de notification
 * @param {Object} options - Options supplémentaires
 */
export function notifyCompat(message, type = 'info', options = {}) {
    switch (type.toLowerCase()) {
        case 'success':
            return success(message, 'Succès', options);
        case 'error':
            return error(message, 'Erreur', options);
        case 'warning':
            return warning(message, 'Attention', options);
        case 'info':
        default:
            return info(message, 'Information', options);
    }
}

/**
 * Notification pour les erreurs de validation de formulaire
 * @param {Object} errors - Objet des erreurs de validation
 */
export function showValidationErrors(errors) {
    Object.entries(errors).forEach(([field, message]) => {
        error(message, `Erreur - ${field}`);
    });
}

// Export de l'objet principal pour une utilisation simplifiée
export default {
    success,
    error,
    warning,
    info,
    custom,
    clearAll,
    handleApiResponse,
    showValidationErrors,
    notify,
    notifyCompat
};