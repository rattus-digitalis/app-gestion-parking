<footer class="site-footer" role="contentinfo">
    <div class="footer-content">
        <div class="footer-brand">
            <strong class="footer-logo">parkly</strong>
            <p class="footer-tagline">Votre solution de stationnement</p>
        </div>
        
        <nav class="footer-links" aria-label="Liens du pied de page">
            <a href="/?page=contact" class="footer-btn" title="Nous contacter">
                Contact
            </a>
            <a href="/?page=cgu" class="footer-btn" title="Conditions générales d'utilisation">
                CGU
            </a>
        </nav>
        
        <div class="footer-social">
            <a href="https://twitter.com/parkly" class="social-link" aria-label="Suivez-nous sur Twitter" target="_blank" rel="noopener">
                Twitter  
            </a>
            <a href="https://facebook.com/parkly" class="social-link" aria-label="Suivez-nous sur Facebook" target="_blank" rel="noopener">
                Facebook
            </a>
            <a href="https://linkedin.com/company/parkly" class="social-link" aria-label="Suivez-nous sur LinkedIn" target="_blank" rel="noopener">
                LinkedIn
            </a>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p class="footer-copyright">
            &copy; <?= date('Y') ?> <strong>parkly</strong>. Aucun droits réservés.
        </p>
    
    </div>
</footer>

<!-- Scripts -->
<script type="module" src="/public/js/app.js"></script>
<script>
    // Script pour améliorer l'accessibilité
    document.addEventListener('DOMContentLoaded', function() {
        // Ajout d'indicateurs visuels pour les liens externes
        const externalLinks = document.querySelectorAll('a[target="_blank"]');
        externalLinks.forEach(link => {
            link.setAttribute('aria-describedby', 'external-link-warning');
        });
        
        // Message d'avertissement pour les liens externes (caché visuellement mais lu par les lecteurs d'écran)
        if (externalLinks.length > 0 && !document.getElementById('external-link-warning')) {
            const warning = document.createElement('span');
            warning.id = 'external-link-warning';
            warning.style.cssText = 'position: absolute; left: -10000px; width: 1px; height: 1px; overflow: hidden;';
            warning.textContent = '(Ouvre dans un nouvel onglet)';
            document.body.appendChild(warning);
        }
    });
</script>

</body>
</html>