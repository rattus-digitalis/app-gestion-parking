<?php
require_once __DIR__ . '/../../app/models/Parking.php';

header('Content-Type: application/json');

try {
    $parking = new Parking();
    echo json_encode($parking->getStatsTempsReel());
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
}
