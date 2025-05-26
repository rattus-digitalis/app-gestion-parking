<h1>Paiement de la réservation</h1>

<p>Montant à payer : <strong><?= number_format($res['amount'] ?? 5.00, 2) ?> €</strong></p>

<form action="/?page=valider_paiement" method="POST" aria-label="Formulaire de paiement">
    <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($res['id']) ?>">

    <fieldset>
        <legend>Méthode de paiement <span aria-hidden="true">*</span></legend>

        <label for="paypal">
            <input type="radio" id="paypal" name="method" value="paypal" required>
            🅿️ PayPal
        </label><br>

        <label for="cb">
            <input type="radio" id="cb" name="method" value="cb" required>
            💳 Carte Bancaire
        </label>
    </fieldset>

    <button type="submit" class="btn btn-primary" aria-label="Payer maintenant">Payer maintenant</button>
</form>
