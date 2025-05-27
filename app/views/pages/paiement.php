<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Templates
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

// Modèles
require_once __DIR__ . '/../../models/Reservation.php';
require_once __DIR__ . '/../../models/Car.php';

// Vérification des paramètres
$reservationId = $_GET['id'] ?? null;
$montant = $_GET['montant'] ?? null;

if (!$reservationId || !$montant) {
    echo "<p class='alert alert-danger'>Informations de paiement manquantes.</p>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

// Récupération des données réservation
$reservationModel = new Reservation();
$reservation = $reservationModel->getReservationById((int)$reservationId);

if (!$reservation) {
    echo "<p class='alert alert-danger'>Réservation introuvable.</p>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

// Récupération véhicule (si défini)
$carModel = new Car();
$vehicule = $reservation['car_id'] ? $carModel->getById((int)$reservation['car_id']) : null;

// Formatage du montant
$prix = number_format((float)$montant, 2, ',', ' ');
?>

<main class="container payment-page" role="main">
    <h1 class="mb-4">💳 Paiement de la réservation</h1>

    <section class="reservation-summary mb-4" aria-label="Résumé de la réservation">
        <p><strong>Place :</strong> <?= htmlspecialchars($reservation['numero_place'] ?? 'N/A') ?> (Étage <?= htmlspecialchars($reservation['etage'] ?? 'N/A') ?>)</p>
        <p><strong>Du :</strong> <time datetime="<?= htmlspecialchars(date('c', strtotime($reservation['date_start']))) ?>">
            <?= date('d/m/Y H:i', strtotime($reservation['date_start'])) ?></time></p>
        <p><strong>Au :</strong> <time datetime="<?= htmlspecialchars(date('c', strtotime($reservation['date_end']))) ?>">
            <?= date('d/m/Y H:i', strtotime($reservation['date_end'])) ?></time></p>
        <p><strong>Type véhicule :</strong> <?= htmlspecialchars($vehicule['type'] ?? 'Non précisé') ?></p>
        <p><strong>Montant à régler :</strong> <span class="text-success font-weight-bold"><?= $prix ?> €</span></p>
    </section>

    <form action="/?page=valider_paiement&id=<?= urlencode($reservation['id']) ?>" method="POST" aria-label="Formulaire de paiement">
        <fieldset>
            <legend>Méthode de paiement :</legend>

            <div>
                <input type="radio" id="cb" name="methode" value="cb" checked required>
                <label for="cb">Carte bancaire</label>
            </div>

            <div>
                <input type="radio" id="paypal" name="methode" value="paypal" required>
                <label for="paypal">PayPal</label>
            </div>
        </fieldset>

        <button type="submit" class="btn btn-primary mt-3" aria-label="Payer la réservation">✅ Payer maintenant</button>
    </form>

    <!-- Zone de rendu PayPal -->
    <div id="paypal-button-container" class="mt-4"></div>
    <p id="result-message"></p>
</main>

<!-- PayPal SDK et script -->
<script
    src="https://www.paypal.com/sdk/js?client-id=test&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo,paylater,card"
    data-sdk-integration-source="developer-studio"
></script>
<script src="/js/app.js"></script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
