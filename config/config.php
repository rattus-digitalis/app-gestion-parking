<?php

$host = getenv('MYSQL_HOST') ?: 'mysql';
$db   = getenv('MYSQL_DATABASE') ?: 'parkly';
$user = getenv('MYSQL_USER') ?: 'rattus';
$pass = getenv('MYSQL_PASSWORD') ?: 'rattus';
$charset = 'utf8mb4';

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
