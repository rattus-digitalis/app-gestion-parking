<?php
$title = "Erreur 500 - Erreur serveur";
$customCss = "/public/css/pages/500.css"; 
include __DIR__ . '/../templates/head.php';
?>

<main class="error-page error-500">
    <h1>Erreur 500</h1>
    <p>
        Une erreur interne est survenue sur le serveur.<br>
        Merci de réessayer plus tard.
    </p>
    <a href="/?page=home" class="btn btn-primary">Retour à l'accueil</a>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
