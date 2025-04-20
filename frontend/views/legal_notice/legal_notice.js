// legal-notice.js

document.addEventListener("DOMContentLoaded", () => {
    const dateElement = document.querySelector(".update-date");
  
    if (dateElement) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      const today = new Date().toLocaleDateString("fr-FR", options);
  
      dateElement.textContent = `Dernière mise à jour : ${today}`;
    }
  
    // Place pour futures interactions ou améliorations de la page
  });