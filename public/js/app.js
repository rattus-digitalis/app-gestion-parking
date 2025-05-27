paypal.Buttons({
  createOrder: function(data, actions) {
    return actions.order.create({
      purchase_units: [{
        amount: {
          value: montant.toString()
        }
      }]
    });
  },
  onApprove: function(data, actions) {
    return actions.order.capture().then(function(details) {
      const name = details.payer.name.given_name;
      document.getElementById('result-message').innerText =
        `✅ Paiement effectué avec succès par ${name}`;
    });
  },
  onError: function(err) {
    document.getElementById('result-message').innerText =
      `❌ Erreur de paiement : ${err.message || err}`;
  }
}).render('#paypal-button-container');
