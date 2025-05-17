// nouvelle-reservation.js

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
  
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      alert("Réservation enregistrée !");
      form.reset();
    });
  });
  