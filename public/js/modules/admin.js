// public/js/modules/admin.js

import { notify } from './utils/notify.js';

document.addEventListener('DOMContentLoaded', function() {
    
    // Gestionnaire pour tous les boutons de suppression
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-user-btn')) {
            e.preventDefault();
            const userId = e.target.dataset.userId;
            const userName = e.target.closest('tr')?.querySelector('td:nth-child(2)')?.textContent + ' ' + 
                           e.target.closest('tr')?.querySelector('td:nth-child(3)')?.textContent || `Utilisateur #${userId}`;
            
            if (confirm(`Êtes-vous sûr de vouloir supprimer ${userName.trim()} ?`)) {
                deleteUser(userId, e.target);
            }
        }
    });
});

async function deleteUser(userId, buttonElement) {
    try {
        console.log('🚀 Suppression utilisateur ID:', userId);
        
        // Désactiver le bouton pendant la requête
        const originalContent = buttonElement.innerHTML;
        buttonElement.disabled = true;
        buttonElement.innerHTML = '⏳';
        
        const response = await fetch('/?page=admin_delete_user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                // ✅ ATTENTION : bien vérifier l'orthographe !
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: `delete_user_id=${encodeURIComponent(userId)}`
        });

        console.log('📡 Statut réponse:', response.status);
        console.log('📋 Content-Type:', response.headers.get('content-type'));
        
        // Vérifier si c'est bien du JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            // Si ce n'est pas du JSON, c'est probablement une redirection ou erreur HTML
            const htmlText = await response.text();
            console.error('❌ Réponse HTML au lieu de JSON:', htmlText.substring(0, 300));
            throw new Error('Le serveur n\'a pas retourné de JSON. Vérifiez les logs PHP.');
        }

        const result = await response.json();
        console.log('✅ Réponse JSON:', result);
        
        if (result.success) {
            // Succès : supprimer la ligne du tableau avec animation
            const row = buttonElement.closest('tr');
            if (row) {
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    console.log('✅ Ligne supprimée du DOM');
                }, 300);
            }
            
            // Afficher le message de succès
            notify(result.message || 'Utilisateur supprimé avec succès', 'success');
            
        } else {
            throw new Error(result.error || 'Erreur inconnue du serveur');
        }
        
    } catch (error) {
        console.error('❌ Erreur dans deleteUser:', error);
        
        // Restaurer le bouton en cas d'erreur
        buttonElement.disabled = false;
        buttonElement.innerHTML = originalContent || '🗑️';
        
        // Afficher l'erreur à l'utilisateur
        notify(`Erreur: ${error.message}`, 'error');
    }
}

// Fonction de notification améliorée
function notify(message, type = 'info') {
    console.log(`📢 Notification [${type.toUpperCase()}]:`, message);
    
    // Supprimer les anciennes notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    // Créer la nouvelle notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Styles CSS inline pour être sûr que ça marche
    const styles = {
        'success': { bg: '#28a745', color: '#fff' },
        'error': { bg: '#dc3545', color: '#fff' },
        'info': { bg: '#17a2b8', color: '#fff' },
        'warning': { bg: '#ffc107', color: '#212529' }
    };
    
    const style = styles[type] || styles.info;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        background-color: ${style.bg};
        color: ${style.color};
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        max-width: 350px;
        word-wrap: break-word;
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    requestAnimationFrame(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    });
    
    // Auto-suppression après 4 secondes
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 4000);
}