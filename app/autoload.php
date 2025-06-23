<?php


// Autoloader centralisé pour tous les modèles
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Parking.php';      // si existe
require_once __DIR__ . '/models/Reservation.php';  // si existe
require_once __DIR__ . '/models/Car.php';          // si existe

// Vérification que les classes sont bien chargées
if (!class_exists('User')) {
    error_log("ERREUR CRITIQUE: Classe User non chargée dans autoload.php");
    die("Erreur de chargement des modèles");
}

error_log("✅ Autoloader: Modèles chargés avec succès");
?>