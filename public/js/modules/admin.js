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
            
            // Afficher le message de succès - using imported notify function
            notify('success', result.message || 'Utilisateur supprimé avec succès');
            
        } else {
            throw new Error(result.error || 'Erreur inconnue du serveur');
        }
        
    } catch (error) {
        console.error('❌ Erreur dans deleteUser:', error);
        
        // Restaurer le bouton en cas d'erreur
        buttonElement.disabled = false;
        buttonElement.innerHTML = originalContent || '🗑️';
        
        // Afficher l'erreur à l'utilisateur - using imported notify function
        notify('error', `Erreur: ${error.message}`);
    }
}

// Export function for app.js
export function initAdmin() {
    console.log('✅ Module admin initialisé');
    // The DOMContentLoaded event listener above will handle the initialization
    // This function is just for compatibility with app.js import
}