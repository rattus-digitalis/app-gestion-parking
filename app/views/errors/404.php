<?php
http_response_code(404);
$title = "Erreur 404 - Page introuvable";
$customCss = "/assets/css/pages/404.css";
include __DIR__ . '/../templates/head.php';
?>

<main class="error-page error-404" role="main">
    <section class="container">
        <h1>Erreur 404 - Page introuvable</h1>
        <p>La page que vous recherchez est introuvable ou n’existe plus.</p>
        <a href="/?page=home" class="btn btn-primary" role="button" aria-label="Retour à la page d’accueil">⬅ Retour à l’accueil</a>
    </section>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
