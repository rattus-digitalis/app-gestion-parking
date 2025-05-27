import { initLogin } from './modules/login.js';
import { initRegister } from './modules/register.js';
import { initDashboard } from './modules/dashboard.js';
import { initReservation } from './modules/reservation.js';
import { initAdmin } from './modules/admin.js';
import { initPayPalButton } from './modules/paypal.js';

// Récupère l’attribut data-page du <body>
const page = document.body.dataset.page;

switch (page) {
  case 'login':
    initLogin();
    break;

  case 'register':
    initRegister();
    break;

  case 'dashboard_user':
    initDashboard();
    break;

  case 'nouvelle_reservation':
  case 'mes_reservations':
    initReservation();

    // Si PayPal est sur cette page, on l'initialise
    const paypalContainer = document.getElementById('paypal-button-container');
    if (paypalContainer) {
      const montant = parseFloat(paypalContainer.dataset.montant);
      if (!isNaN(montant)) {
        initPayPalButton(montant);
      }
    }
    break;

  case 'admin':
    initAdmin();
    break;

  default:
    // Aucune action JS spécifique pour cette page
    break;
}
