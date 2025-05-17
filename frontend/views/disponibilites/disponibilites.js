// disponibilites.js

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("dispo-form");
    const resultats = document.getElementById("resultats");
  
    form.addEventListener("submit", (e) => {
      e.preventDefault();
  
      const date = form.date.value;
      const heure = form.heure.value;
  
      // Simulation des données (à remplacer par appel API plus tard)
      const placesDisponibles = Math.floor(Math.random() * 10) + 1;
  
      resultats.innerHTML = `
        <p><strong>${placesDisponibles}</strong> place(s) disponible(s) le <strong>${date}</strong> à <strong>${heure}</strong>.</p>
      `;
      resultats.style.display = "block";
    });
  });
  