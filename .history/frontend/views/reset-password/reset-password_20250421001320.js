// reset-password.js

document.addEventListener("DOMContentLoaded", () => {
    console.log("Page Reset Password chargée");
  
    // Exemple simple (à adapter avec API réelle)
    const form = document.querySelector("form");
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      alert("Un lien de réinitialisation a été envoyé à votre adresse email.");
      form.reset();
    });
  });
  