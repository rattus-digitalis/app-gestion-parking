<?php
// Configuration PayPal pour Zenpark

// Mode sandbox (développement) ou live (production)
define('PAYPAL_MODE', 'sandbox'); // Changez en 'live' pour la production

// URLs des API PayPal
define('PAYPAL_API_URL', PAYPAL_MODE === 'sandbox' 
    ? 'https://api-m.sandbox.paypal.com' 
    : 'https://api-m.paypal.com'
);

// Clés PayPal (à remplacer par vos vraies clés)
if (PAYPAL_MODE === 'sandbox') {
    // Clés de test PayPal Sandbox
    define('PAYPAL_CLIENT_ID', 'votre_client_id_sandbox');
    define('PAYPAL_CLIENT_SECRET', 'votre_client_secret_sandbox');
} else {
    // Clés de production PayPal Live
    define('PAYPAL_CLIENT_ID', 'votre_client_id_live');
    define('PAYPAL_CLIENT_SECRET', 'votre_client_secret_live');
}

// Configuration webhook (optionnel mais recommandé)
define('PAYPAL_WEBHOOK_ID', 'votre_webhook_id');

// URL de base de votre site
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost'); // Changez selon votre environnement
}

// Devise par défaut
define('PAYPAL_CURRENCY', 'EUR');

// Configuration des timeouts
define('PAYPAL_TIMEOUT', 30); // secondes

// Messages d'erreur personnalisés
define('PAYPAL_ERROR_MESSAGES', [
    'INVALID_CLIENT' => 'Configuration PayPal invalide',
    'INSUFFICIENT_FUNDS' => 'Fonds insuffisants sur le compte PayPal',
    'PAYMENT_ALREADY_DONE' => 'Ce paiement a déjà été effectué',
    'INVALID_PAYMENT_METHOD' => 'Méthode de paiement non autorisée'
]);
?>