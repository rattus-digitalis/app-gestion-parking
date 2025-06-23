<?php

$host = getenv('MYSQL_HOST') ?: 'mysql';
$db   = getenv('MYSQL_DATABASE') ?: 'parkly';
$user = getenv('MYSQL_USER') ?: 'rattus';
$pass = getenv('MYSQL_PASSWORD') ?: 'rattus';
$charset = 'utf8mb4';

// Define constants only if not already defined
if (!defined('DB_HOST')) define('DB_HOST', $host);
if (!defined('DB_NAME')) define('DB_NAME', $db);
if (!defined('DB_USER')) define('DB_USER', $user);
if (!defined('DB_PASS')) define('DB_PASS', $pass);
if (!defined('DB_CHARSET')) define('DB_CHARSET', $charset);

// Include PayPal config if it exists
if (file_exists(__DIR__ . '/paypal.php')) {
    require_once __DIR__ . '/paypal.php';
} else {
    // Default PayPal configuration if paypal.php doesn't exist
    if (!defined('PAYPAL_ENV')) define('PAYPAL_ENV', getenv('PAYPAL_ENV') ?: 'sandbox');
    if (!defined('PAYPAL_CLIENT_ID')) define('PAYPAL_CLIENT_ID', getenv('PAYPAL_CLIENT_ID') ?: 'your_sandbox_client_id');
    if (!defined('PAYPAL_CLIENT_SECRET')) define('PAYPAL_CLIENT_SECRET', getenv('PAYPAL_CLIENT_SECRET') ?: 'your_sandbox_client_secret');
    if (!defined('PAYPAL_BASE_URL')) define('PAYPAL_BASE_URL', PAYPAL_ENV === 'sandbox' ? 'https://api.sandbox.paypal.com' : 'https://api.paypal.com');
}

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}