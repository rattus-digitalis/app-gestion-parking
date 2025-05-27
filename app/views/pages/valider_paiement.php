<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
require_once __DIR__ . '/../../models/Reservation.php';
require_once __DIR__ . '/../../models/Car.php';

// Récupération des paramètres
$reservationId = $_GET['id'] ?? null;
$montant = $_GET['montant'] ?? null;

if (!$reservationId || !$montant || !is_numeric($montant)) {
    echo "<p class='alert alert-danger'>❌ Informations de paiement manquantes ou invalides.</p>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

// Récupération des données réservation
$reservationModel = new Reservation();
$reservation = $reservationModel->getReservationById((int)$reservationId);

if (!$reservation) {
    echo "<p class='alert alert-danger'>❌ Réservation introuvable.</p>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

// Récupération véhicule (si défini)
$carModel = new Car();
$vehicule = $reservation['car_id'] ? $carModel->getById((int)$reservation['car_id']) : null;

// Formatage du montant
$prix = number_format((float)$montant, 2, ',', ' ');
$prixJs = number_format((float)$montant, 2, '.', ''); // pour JS (avec point)
?>

<main class="container payment-page" role="main">
    <h1 class="mb-4">🔐 Paiement sécurisé</h1>

    <section class="reservation-summary mb-4" aria-label="Résumé de la réservation">
        <p><strong>Réservation :</strong> Place <?= htmlspecialchars($reservation['numero_place']) ?> (Étage <?= htmlspecialchars($reservation['etage']) ?>)</p>
        <p><strong>Du :</strong> <?= date('d/m/Y H:i', strtotime($reservation['date_start'])) ?></p>
        <p><strong>Au :</strong> <?= date('d/m/Y H:i', strtotime($reservation['date_end'])) ?></p>
        <p><strong>Type véhicule :</strong> <?= htmlspecialchars($vehicule['type'] ?? 'Non précisé') ?></p>
        <p><strong>Montant à régler :</strong> <span class="text-success font-weight-bold"><?= $prix ?> €</span></p>
    </section>

    <div id="paypal-button-container" class="mb-3"></div>
    <p id="result-message" class="text-info"></p>
</main>

<script>
    const montant = <?= json_encode($prixJs) ?>;
</script>

<!-- SDK PayPal avec fausse clé 'sb' -->
<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=EUR&components=buttons&enable-funding=paypal,card"></script>
<script src="/js/app.js"></script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
