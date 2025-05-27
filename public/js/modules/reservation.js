import { post } from './utils/fetch.js';
import { notify } from './utils/notify.js';

export function initReservation() {
  const form = document.querySelector('#reservation-form');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
      parking_id: form.parking.value,
      date_debut: form.date_debut.value,
      date_fin: form.date_fin.value
    };

    try {
      const result = await post('/app/controllers/ReservationController.php', data);
      if (result.success) {
        notify('success', 'Réservation effectuée');
        window.location.href = '/mes_reservations';
      } else {
        notify('error', result.message || 'Erreur de réservation');
      }
    } catch (error) {
      notify('error', 'Erreur serveur');
      console.error(error);
    }
  });
}
