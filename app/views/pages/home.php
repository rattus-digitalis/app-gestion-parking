<?php
declare(strict_types=1);
$title = "Accueil";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="home-main">
    <section class="home-content">
        <h1>
            Bienvenue sur <strong>Zenpark</strong>
        </h1>

        <p>
            Gérez vos réservations de places de parking <em>simplement</em> et <em>efficacement</em>.
        </p>

        <div class="home-buttons">
            <a href="/?page=login" class="btn btn-primary">Se connecter</a>
            <a href="/?page=register" class="btn btn-secondary">Créer un compte</a>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
