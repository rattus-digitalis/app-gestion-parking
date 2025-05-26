<h1>Paiement de la réservation</h1>

<p>Montant à payer : <strong>5,00 €</strong></p>

<form action="/?page=valider_paiement" method="POST">
    <input type="hidden" name="reservation_id" value="<?= $res['id'] ?>">
    
    <label>
        <input type="radio" name="method" value="paypal" required>
        🅿️ PayPal
    </label><br>

    <label>
        <input type="radio" name="method" value="cb" required>
        💳 Carte Bancaire
    </label><br><br>

    <button type="submit">Payer maintenant</button>
</form>
