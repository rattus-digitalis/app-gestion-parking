<?php
/**
 * Configuration PayPal pour Zenpark
 * Fichier simplifié sans erreurs
 */

// Mode sandbox (développement) ou live (production)
if (!defined('PAYPAL_MODE')) {
    define('PAYPAL_MODE', 'sandbox');
}

// URLs des API PayPal
if (!defined('PAYPAL_API_URL')) {
    define('PAYPAL_API_URL', PAYPAL_MODE === 'sandbox' 
        ? 'https://api-m.sandbox.paypal.com' 
        : 'https://api-m.paypal.com'
    );
}

// Clés PayPal - REMPLACEZ par vos vraies clés
if (PAYPAL_MODE === 'sandbox') {
    // Clés de test PayPal Sandbox
    if (!defined('PAYPAL_CLIENT_ID')) {
        define('PAYPAL_CLIENT_ID', 'test_client_id_sandbox');
    }
    if (!defined('PAYPAL_CLIENT_SECRET')) {
        define('PAYPAL_CLIENT_SECRET', 'test_client_secret_sandbox');
    }
} else {
    // Clés de production PayPal Live
    if (!defined('PAYPAL_CLIENT_ID')) {
        define('PAYPAL_CLIENT_ID', 'your_live_client_id');
    }
    if (!defined('PAYPAL_CLIENT_SECRET')) {
        define('PAYPAL_CLIENT_SECRET', 'your_live_client_secret');
    }
}

// URL de base de votre site
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost');
}

// Devise par défaut
if (!defined('PAYPAL_CURRENCY')) {
    define('PAYPAL_CURRENCY', 'EUR');
}

// Configuration des timeouts
if (!defined('PAYPAL_TIMEOUT')) {
    define('PAYPAL_TIMEOUT', 30);
}

// Messages d'erreur personnalisés
if (!defined('PAYPAL_ERROR_MESSAGES')) {
    define('PAYPAL_ERROR_MESSAGES', serialize([
        'INVALID_CLIENT' => 'Configuration PayPal invalide',
        'INSUFFICIENT_FUNDS' => 'Fonds insuffisants sur le compte PayPal',
        'PAYMENT_ALREADY_DONE' => 'Ce paiement a déjà été effectué',
        'INVALID_PAYMENT_METHOD' => 'Méthode de paiement non autorisée'
    ]));
}