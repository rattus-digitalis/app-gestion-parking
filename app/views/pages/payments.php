<?php
$title = "Paiement";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container payment-page" role="main">
    <h1>Paiement de la réservation</h1>

    <section class="reservation-summary" aria-label="Résumé de la réservation">
        <p><strong>Place :</strong> <?= htmlspecialchars($reservation['numero_place']) ?> (Étage <?= htmlspecialchars($reservation['etage']) ?>)</p>
        <p><strong>Du :</strong> <time datetime="<?= htmlspecialchars(date('c', strtotime($reservation['date_start']))) ?>"><?= date('d/m/Y H:i', strtotime($reservation['date_start'])) ?></time></p>
        <p><strong>Au :</strong> <time datetime="<?= htmlspecialchars(date('c', strtotime($reservation['date_end']))) ?>"><?= date('d/m/Y H:i', strtotime($reservation['date_end'])) ?></time></p>
        <p><strong>Type véhicule :</strong> <?= htmlspecialchars($vehicule['type']) ?></p>
        <p><strong>Montant à régler :</strong> <strong><?= number_format($prix, 2, ',', ' ') ?> €</strong></p>
    </section>

    <form action="/?page=valider_paiement&id=<?= urlencode($reservation['id']) ?>" method="POST" aria-label="Formulaire de paiement">
        <fieldset>
            <legend>Méthode de paiement :</legend>

            <div>
                <input type="radio" id="cb" name="methode" value="cb" checked required>
                <label for="cb">💳 Carte bancaire</label>
            </div>

            <div>
                <input type="radio" id="paypal" name="methode" value="paypal" required>
                <label for="paypal">🅿️ PayPal</label>
            </div>
        </fieldset>

        <button type="submit" class="btn btn-primary" aria-label="Payer la réservation">✅ Payer</button>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
