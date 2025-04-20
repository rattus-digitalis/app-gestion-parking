// confirm-email.js

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
  
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      alert("Un nouveau lien de confirmation a été envoyé à votre adresse email.");
      form.reset();
    });
  });
  