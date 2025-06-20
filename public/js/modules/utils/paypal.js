// public/js/modules/utils/paypal.js

/**
 * Configuration PayPal
 */
const PAYPAL_CONFIG = {
    clientId: 'YOUR_PAYPAL_CLIENT_ID', // À remplacer par votre vrai client ID
    currency: 'EUR',
    intent: 'capture',
    environment: 'sandbox' // 'sandbox' ou 'production'
};

/**
 * Initialise les boutons PayPal sur la page
 */
export function initPayPalButton() {
    console.log('🔄 Initialisation du module PayPal...');
    
    // Vérifier si PayPal SDK est chargé
    if (typeof paypal === 'undefined') {
        console.warn('⚠️ PayPal SDK non chargé. Ajoutez le script PayPal à votre HTML.');
        return;
    }
    
    // Chercher tous les conteneurs de boutons PayPal
    const paypalContainers = document.querySelectorAll('.paypal-button-container');
    
    if (paypalContainers.length === 0) {
        console.log('ℹ️ Aucun conteneur PayPal trouvé sur cette page.');
        return;
    }
    
    paypalContainers.forEach((container, index) => {
        initSinglePayPalButton(container, index);
    });
}

/**
 * Initialise un bouton PayPal individuel
 */
function initSinglePayPalButton(container, index = 0) {
    const amount = container.dataset.amount || '10.00';
    const description = container.dataset.description || 'Achat Parkly';
    const orderId = container.dataset.orderId || `order_${Date.now()}_${index}`;
    
    console.log(`💳 Création bouton PayPal #${index} - Montant: ${amount}€`);
    
    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'gold',
            shape: 'rect',
            label: 'paypal'
        },
        
        createOrder: function(data, actions) {
            console.log('🛒 Création de la commande PayPal...');
            
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: amount,
                        currency_code: PAYPAL_CONFIG.currency
                    },
                    description: description,
                    custom_id: orderId
                }]
            });
        },
        
        onApprove: function(data, actions) {
            console.log('✅ Paiement approuvé:', data);
            
            return actions.order.capture().then(function(details) {
                console.log('💰 Paiement capturé:', details);
                
                // Envoyer les détails au serveur
                return handlePaymentSuccess(details, orderId);
            });
        },
        
        onError: function(err) {
            console.error('❌ Erreur PayPal:', err);
            showPaymentError('Une erreur est survenue avec PayPal. Veuillez réessayer.');
        },
        
        onCancel: function(data) {
            console.log('🚫 Paiement annulé:', data);
            showPaymentInfo('Paiement annulé.');
        }
        
    }).render(container);
}

/**
 * Gère le succès du paiement
 */
async function handlePaymentSuccess(paypalDetails, orderId) {
    try {
        console.log('📤 Envoi des détails de paiement au serveur...');
        
        const response = await fetch('/?page=payment_success', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                paypal_details: paypalDetails,
                order_id: orderId,
                payment_method: 'paypal'
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showPaymentSuccess(result.message || 'Paiement confirmé !');
            
            // Redirection après succès
            if (result.redirect_url) {
                setTimeout(() => {
                    window.location.href = result.redirect_url;
                }, 2000);
            }
        } else {
            throw new Error(result.message || 'Erreur de traitement du paiement');
        }
        
    } catch (error) {
        console.error('❌ Erreur traitement paiement:', error);
        showPaymentError(`Erreur: ${error.message}`);
    }
}

/**
 * Fonctions d'affichage des messages
 */
function showPaymentSuccess(message) {
    console.log('✅ Succès:', message);
    // Utiliser le système de notification si disponible
    if (typeof notify !== 'undefined') {
        notify('success', message);
    } else {
        alert(`Succès: ${message}`);
    }
}

function showPaymentError(message) {
    console.error('❌ Erreur:', message);
    if (typeof notify !== 'undefined') {
        notify('error', message);
    } else {
        alert(`Erreur: ${message}`);
    }
}

function showPaymentInfo(message) {
    console.log('ℹ️ Info:', message);
    if (typeof notify !== 'undefined') {
        notify('info', message);
    } else {
        alert(message);
    }
}

/**
 * Vérifie si PayPal est disponible et charge le SDK si nécessaire
 */
export function loadPayPalSDK(clientId = PAYPAL_CONFIG.clientId) {
    return new Promise((resolve, reject) => {
        if (typeof paypal !== 'undefined') {
            resolve(paypal);
            return;
        }
        
        const script = document.createElement('script');
        script.src = `https://www.paypal.com/sdk/js?client-id=${clientId}&currency=${PAYPAL_CONFIG.currency}`;
        script.onload = () => resolve(window.paypal);
        script.onerror = () => reject(new Error('Impossible de charger PayPal SDK'));
        
        document.head.appendChild(script);
    });
}

/**
 * Initialisation automatique au chargement de la page
 */
document.addEventListener('DOMContentLoaded', () => {
    // Auto-initialisation si des conteneurs PayPal sont présents
    if (document.querySelector('.paypal-button-container')) {
        initPayPalButton();
    }
});

/**
 * Utilitaires PayPal
 */
export const PayPalUtils = {
    /**
     * Formate un montant pour PayPal
     */
    formatAmount(amount) {
        return parseFloat(amount).toFixed(2);
    },
    
    /**
     * Valide un montant PayPal
     */
    validateAmount(amount) {
        const num = parseFloat(amount);
        return !isNaN(num) && num > 0 && num <= 10000;
    },
    
    /**
     * Génère un ID de commande unique
     */
    generateOrderId(prefix = 'parkly') {
        return `${prefix}_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
    }
};

// Export par défaut
export default {
    initPayPalButton,
    loadPayPalSDK,
    PayPalUtils
};