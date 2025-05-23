<?php
declare(strict_types=1);
$title = "Accueil";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main style="padding: 60px 20px; text-align: center; font-family: sans-serif;">
    <section style="max-width: 800px; margin: auto;">
        <h1 style="font-size: 2.5rem; margin-bottom: 20px;">
            Bienvenue sur <strong>Zenpark</strong>
        </h1>

        <p style="font-size: 1.2rem; margin-bottom: 30px;">
            Gérez vos réservations de places de parking <em>simplement</em> et <em>efficacement</em>.
        </p>

        <div style="margin-top: 40px;">
            <a href="/?page=login"
               style="background-color: #2d89ef; color: white; padding: 12px 25px; border-radius: 6px; text-decoration: none; margin: 10px; display: inline-block;">
               Se connecter
            </a>

            <a href="/?page=register"
               style="background-color: #555; color: white; padding: 12px 25px; border-radius: 6px; text-decoration: none; margin: 10px; display: inline-block;">
               Créer un compte
            </a>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>

