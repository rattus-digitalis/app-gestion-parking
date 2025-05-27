import { post } from './utils/fetch.js';
import { notify } from './utils/notify.js';

export function initLogin() {
  const form = document.querySelector('#login-form');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
      email: form.email.value,
      password: form.password.value
    };

    try {
      const result = await post('/app/controllers/LoginController.php', data);
      if (result.success) {
        notify('success', 'Connexion réussie');
        window.location.href = '/dashboard';
      } else {
        notify('error', result.message || 'Email ou mot de passe incorrect');
      }
    } catch (error) {
      notify('error', 'Erreur serveur');
      console.error(error);
    }
  });
}
