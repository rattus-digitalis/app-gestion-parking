function includeHTML(selector, filePath) {
  console.log(`Chargement de ${filePath} dans ${selector}`);
  fetch(filePath)
    .then(response => {
      if (!response.ok) throw new Error(`Erreur de chargement : ${filePath}`);
      return response.text();
    })
    .then(html => {
      const target = document.querySelector(selector);
      if (target) {
        target.innerHTML = html;
        console.log(`✔️ ${filePath} chargé avec succès`);
      }
    })
    .catch(err => console.error("❌", err));
}
