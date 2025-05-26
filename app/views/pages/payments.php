<?php
$title = "Paiement";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Paiement de la réservation</h1>

    <p><strong>Place :</strong> <?= htmlspecialchars($reservation['numero_place']) ?> (Étage <?= htmlspecialchars($reservation['etage']) ?>)</p>
    <p><strong>Du :</strong> <?= date('d/m/Y H:i', strtotime($reservation['date_start'])) ?></p>
    <p><strong>Au :</strong> <?= date('d/m/Y H:i', strtotime($reservation['date_end'])) ?></p>
    <p><strong>Type véhicule :</strong> <?= htmlspecialchars($vehicule['type']) ?></p>
    <p><strong>Montant à régler :</strong> <strong><?= number_format($prix, 2, ',', ' ') ?> €</strong></p>

    <form action="/?page=valider_paiement&id=<?= $reservation['id'] ?>" method="POST">
        <label>Méthode de paiement :</label><br>
        <input type="radio" name="methode" value="cb" id="cb" checked>
        <label for="cb">💳 Carte bancaire</label><br>

        <input type="radio" name="methode" value="paypal" id="paypal">
        <label for="paypal">🅿️ PayPal</label><br><br>

        <button type="submit">✅ Payer</button>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
