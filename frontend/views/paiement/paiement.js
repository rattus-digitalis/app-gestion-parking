const montantField = document.getElementById("montant");
const confirmation = document.getElementById("confirmation");

paypal.Buttons({
  style: {
    layout: 'vertical',
    color: 'blue',
    shape: 'pill',
    label: 'paypal',
    height: 45
  },
  funding: {
    allowed: [paypal.FUNDING.PAYPAL, paypal.FUNDING.CARD]
  },
  createOrder: function(data, actions) {
    return actions.order.create({
      purchase_units: [{
        amount: {
          value: montantField.value.trim()
        }
      }]
    });
  },
  onApprove: function(data, actions) {
    return actions.order.capture().then(function(details) {
      confirmation.innerHTML = `<p>✅ Paiement effectué par ${details.payer.name.given_name} ${details.payer.name.surname}</p>`;
      confirmation.style.display = 'block';
    });
  },
  onError: function(err) {
    console.error("Erreur PayPal :", err);
    alert("Erreur lors du paiement.");
  }
}).render('#paypal-button-container');
