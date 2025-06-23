<?php
http_response_code(500);
$title = "Erreur 500 - Erreur serveur";
$customCss = "/public/css/pages/500.css";
include __DIR__ . '/../templates/head.php';
?>

<main class="error-page error-500" role="main">
    <section class="container">
        <h1>Erreur 500 - Erreur serveur</h1>
        <p>
            Une erreur interne est survenue sur le serveur.<br>
            Merci de réessayer plus tard.
        </p>
        <a href="/?page=home" class="btn btn-primary" role="button" aria-label="Retour à la page d’accueil">⬅ Retour à l’accueil</a>
    </section>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
