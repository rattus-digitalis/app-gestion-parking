<?php
/**
 * Configuration PayPal pour Zenpark
 */

// Mode sandbox (développement) ou live (production)
if (!defined('PAYPAL_MODE')) {
    define('PAYPAL_MODE', 'sandbox');
}

// URLs des API PayPal
if (!defined('PAYPAL_API_URL')) {
    define('PAYPAL_API_URL', 'https://api-m.sandbox.paypal.com');
}

// Clés PayPal - VOS VRAIES CLÉS
if (!defined('PAYPAL_CLIENT_ID')) {
    define('PAYPAL_CLIENT_ID', 'AbB8di9Z--U4bMEwQ_WbbvtrFhucU4x6y_oL6sBoLIAJfOzGXGSBDMVN7O30-dGflH1z4imNwRt7Q0D8');
}
if (!defined('PAYPAL_CLIENT_SECRET')) {
    define('PAYPAL_CLIENT_SECRET', 'EFs9rXrkvgj7HFAT-30MaJ_v5zpN9lrfhGqrVfyADgLLB2SCendeFk5PCCFQuBVuMZV7CCrwqQ-vExOA');
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