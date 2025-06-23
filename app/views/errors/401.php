<?php
$title = "Erreur 401 - Accès refusé";
$customCss = "/public/css/pages/401.css";
include __DIR__ . '/../templates/head.php';
?>

<main class="error-page error-401" role="main">
    <section class="container">
        <h1>Erreur 401 - Accès refusé</h1>
        <p>Vous n’avez pas les autorisations nécessaires pour accéder à cette page.</p>
        <a href="/?page=home" class="btn btn-primary" role="button" aria-label="Retour à la page d’accueil">⬅ Retour à l’accueil</a>
    </section>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
