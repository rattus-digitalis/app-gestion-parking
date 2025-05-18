<nav>
    <ul>
        <li><a href="/?page=home">Accueil</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="/?page=dashboard">Dashboard</a></li>
            <li><a href="/?page=logout">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="/?page=login">Connexion</a></li>
            <li><a href="/?page=register">Créer un compte</a></li>
        <?php endif; ?>
    </ul>
</nav>
