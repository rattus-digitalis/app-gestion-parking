function includeHTML(selector, filePath) {
    fetch(filePath)
      .then(response => {
        if (!response.ok) throw new Error(`Erreur de chargement : ${filePath}`);
        return response.text();
      })
      .then(html => {
        const target = document.querySelector(selector);
        if (target) target.innerHTML = html;
      })
      .catch(err => console.error(err));
  }
  
  document.addEventListener("DOMContentLoaded", () => {
    includeHTML("#header", "/components/header/header.html");
    includeHTML("#footer", "/components/footer/footer.html");
  });
  