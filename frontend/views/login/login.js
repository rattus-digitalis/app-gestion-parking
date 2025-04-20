// Login.js

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".login-form");
  
    if (form) {
      form.addEventListener("submit", (e) => {
        e.preventDefault(); // Empêche le rechargement
  
        const email = form.email.value.trim();
        const password = form.password.value.trim();
  
        if (!email || !password) {
          alert("Veuillez remplir tous les champs.");
          return;
        }
  
        // Ici, tu pourrais envoyer les données à ton backend
        alert("Connexion réussie ! Bienvenue ✨");
        form.reset();
      });
    }
  });