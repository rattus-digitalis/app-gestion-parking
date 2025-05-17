// paiement.js

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("paiement-form");
    const confirmation = document.getElementById("confirmation");
  
    form.addEventListener("submit", (e) => {
      e.preventDefault();
  
      const methode = document.querySelector('input[name="methode"]:checked').value;
  
      // Simuler un paiement
      console.log(`Paiement via ${methode} en cours...`);
  
      // Afficher confirmation
      confirmation.style.display = "block";
      form.style.display = "none";
    });
  });
  