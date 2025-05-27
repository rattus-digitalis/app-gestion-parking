import { post } from './utils/fetch.js';
import { notify } from './utils/notify.js';

export function initRegister() {
  const form = document.querySelector('#register-form');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
      email: form.email.value,
      password: form.password.value,
      confirmPassword: form.confirmPassword.value
    };

    if (data.password !== data.confirmPassword) {
      notify('error', 'Les mots de passe ne correspondent pas');
      return;
    }

    try {
      const result = await post('/app/controllers/UserController.php', data);
      if (result.success) {
        notify('success', 'Compte créé');
        window.location.href = '/login';
      } else {
        notify('error', result.message || 'Erreur lors de la création du compte');
      }
    } catch (error) {
      notify('error', 'Erreur serveur');
      console.error(error);
    }
  });
}
