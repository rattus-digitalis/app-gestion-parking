<nav class="site-nav" role="navigation" aria-label="Menu principal">
    <ul class="nav-list">
        <li><a href="/?page=home" class="nav-link">Accueil</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="/?page=dashboard" class="nav-link">Dashboard</a></li>
            <li><a href="/?page=logout" class="nav-link">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="/?page=login" class="nav-link">Connexion</a></li>
            <li><a href="/?page=register" class="nav-link">Créer un compte</a></li>
        <?php endif; ?>
    </ul>
</nav>
