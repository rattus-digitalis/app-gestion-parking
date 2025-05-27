import { notify } from './utils/notify.js';

export function initAdmin() {
  const deleteButtons = document.querySelectorAll('[data-delete]');
  deleteButtons.forEach(button => {
    button.addEventListener('click', () => {
      const id = button.dataset.delete;
      if (confirm('Confirmer la suppression ?')) {
        fetch(`/app/controllers/AdminUserController.php?id=${id}`, { method: 'DELETE' })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              notify('success', 'Utilisateur supprimé');
              location.reload();
            } else {
              notify('error', 'Erreur de suppression');
            }
          });
      }
    });
  });
}
