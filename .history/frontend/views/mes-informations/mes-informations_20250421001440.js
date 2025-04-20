// mes-informations.js

document.addEventListener("DOMContentLoaded", () => {
    console.log("Page Mes informations chargée");
  
    const form = document.querySelector("form");
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      alert("Vos informations ont bien été mises à jour.");
    });
  });
  