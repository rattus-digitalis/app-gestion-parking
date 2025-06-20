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
    <a href="https://twitter.com/parkly" 
       class="social-link" 
       aria-label="Suivez Parkly sur Twitter - Restez informé de nos dernières actualités" 
       target="_blank" 
       rel="noopener noreferrer">
        <span class="social-text">Twitter</span>
    </a>
    
    <a href="https://facebook.com/parkly" 
       class="social-link" 
       aria-label="Rejoignez la communauté Parkly sur Facebook" 
       target="_blank" 
       rel="noopener noreferrer">
        <span class="social-text">Facebook</span>
    </a>
    
    <a href="https://linkedin.com/company/parkly" 
       class="social-link" 
       aria-label="Connectez-vous avec Parkly sur LinkedIn - Réseau professionnel" 
       target="_blank" 
       rel="noopener noreferrer">
        <span class="social-text">LinkedIn</span>
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
<?php
$mainJs = '/js/app.js';
$mainJsPath = $_SERVER['DOCUMENT_ROOT'] . $mainJs;
if (file_exists($mainJsPath)) :
    $mainJsVersion = filemtime($mainJsPath);
?>
    <script type="module" src="<?= $mainJs ?>?v=<?= $mainJsVersion ?>"></script>
<?php else: ?>
    <!-- ⚠️ JS principal introuvable : <?= $mainJs ?> -->
<?php endif; ?>

</body>
</html>