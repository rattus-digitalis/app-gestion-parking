<?php
$title = "Accueil";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <section>
        <h1>Bienvenue sur Zenpark</h1>
        <p>Gérez vos réservations de places de parking simplement et efficacement.</p>

        <div class="home-actions">
            <a href="/?page=login">Se connecter</a> |
            <a href="/?page=register">Créer un compte</a>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
