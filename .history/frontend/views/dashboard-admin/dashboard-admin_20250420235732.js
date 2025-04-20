// dashboard-admin.js

// Fonction de mise en surbrillance du lien actif
document.addEventListener('DOMContentLoaded', () => {
    const sidebarLinks = document.querySelectorAll('.sidebar a');
    const currentPage = window.location.pathname.split('/').pop();
  
    sidebarLinks.forEach(link => {
      const href = link.getAttribute('href');
      if (href && href.includes(currentPage)) {
        link.classList.add('active');
      }
    });
  });
  