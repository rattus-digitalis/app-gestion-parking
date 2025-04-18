function includeHTML(selector, filePath) {
    fetch(filePath)
      .then(response => {
        if (!response.ok) throw new Error("Erreur de chargement : " + filePath);
        return response.text();
      })
      .then(data => {
        document.querySelector(selector).innerHTML = data;
      })
      .catch(err => console.error(err));
  }
  
  document.addEventListener("DOMContentLoaded", () => {
    includeHTML("#footer", "/components/footer/footer.html");
  });
  
  function includeHTML(selector, filePath) {
    fetch(filePath)
      .then(res => {
        if (!res.ok) throw new Error(`Erreur de chargement : ${filePath}`);
        return res.text();
      })
      .then(data => {
        document.querySelector(selector).innerHTML = data;
      })
      .catch(err => console.error(err));
  }
  
  document.addEventListener("DOMContentLoaded", () => {
    includeHTML("#header", "/components/header/Header.html");
    includeHTML("#footer", "/components/footer/Footer.html");
  });
  