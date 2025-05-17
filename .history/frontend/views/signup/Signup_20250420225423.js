// Signup.js

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".signup-form");
  
    if (form) {
      form.addEventListener("submit", (e) => {
        e.preventDefault();
  
        const name = form.name.value.trim();
        const email = form.email.value.trim();
        const password = form.password.value.trim();
        const confirmPassword = form["confirm-password"].value.trim();
  
        if (!name || !email || !password || !confirmPassword) {
          alert("Veuillez remplir tous les champs.");
          return;
        }
  
        if (password !== confirmPassword) {
          alert("Les mots de passe ne correspondent pas.");
          return;
        }
  
        alert("Inscription réussie ! Bienvenue 🌟");
        form.reset();
      });
    }
  });