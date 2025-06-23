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
    </div>

    <div class="footer-bottom">
        <p class="footer-copyright">
            &copy; <?= date('Y') ?> <strong>parkly</strong>. Aucun droits réservés.
        </p>
    </div>
</footer>

<!-- Script accessibilité -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const externalLinks = document.querySelectorAll('a[target="_blank"]');
        externalLinks.forEach(link => {
            link.setAttribute('aria-describedby', 'external-link-warning');
        });

        if (externalLinks.length > 0 && !document.getElementById('external-link-warning')) {
            const warning = document.createElement('span');
            warning.id = 'external-link-warning';
            warning.style.cssText = 'position: absolute; left: -10000px; width: 1px; height: 1px; overflow: hidden;';
            warning.textContent = '(Ouvre dans un nouvel onglet)';
            document.body.appendChild(warning);
        }
    });
</script>

<!-- Script JS principal -->
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
