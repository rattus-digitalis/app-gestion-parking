// include.js

// Fonction unique avec anti-cache
function includeHTML(selector, filePath) {
  const noCacheURL = filePath + '?v=' + new Date().getTime(); // empêche le cache
  fetch(noCacheURL)
    .then(response => {
      if (!response.ok) throw new Error("Erreur de chargement : " + filePath);
      return response.text();
    })
    .then(data => {
      document.querySelector(selector).innerHTML = data;
    })
    .catch(err => console.error(err));
}

document.add
