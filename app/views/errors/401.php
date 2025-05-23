<?php
$title = "Erreur 401 - Accès refusé";
$customCss = "/public/css/pages/401.css"; // Indique à head.php de charger ce fichier
include __DIR__ . '/../templates/head.php';
?>

<main class="error-page error-401">
    <h1>Erreur 401</h1>
    <p>Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
    <a href="/?page=home" class="btn btn-primary">Retour à l'accueil</a>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
