// Contact.js

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".contact-form");
  
    if (form) {
      form.addEventListener("submit", (e) => {
        e.preventDefault(); // empêche l'envoi réel
  
        // Optionnel : récupération des champs
        const name = form.name.value.trim();
        const email = form.email.value.trim();
        const message = form.message.value.trim();
  
        if (!name || !email || !message) {
          alert("Veuillez remplir tous les champs.");
          return;
        }
  
        // Affichage du message de confirmation
        alert("Merci pour votre message ! Nous vous répondrons rapidement.");
  
        form.reset();
      });
    }
  });