document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("toggle-theme");
    const html = document.documentElement;
  
    // Appliquer le thème stocké ou celui du système
    const savedTheme = localStorage.getItem("theme");
  
    if (savedTheme) {
      html.setAttribute("data-theme", savedTheme);
    } else {
      const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
      html.setAttribute("data-theme", prefersDark ? "dark" : "light");
    }
  
    // Bascule manuelle au clic
    if (toggleBtn) {
      toggleBtn.addEventListener("click", () => {
        const current = html.getAttribute("data-theme");
        const newTheme = current === "dark" ? "light" : "dark";
        html.setAttribute("data-theme", newTheme);
        localStorage.setItem("theme", newTheme);
  
        // (Optionnel) Afficher le thème actif dans la console
        console.log(`Thème actuel : ${newTheme}`);
      });
    }
  });
  