<?php
$title = "Erreur 404 - Page introuvable";
$customCss = "/public/css/pages/404.css"; 
include __DIR__ . '/../templates/head.php';
?>

<main class="error-page error-404">
    <h1>Erreur 404</h1>
    <p>La page que vous cherchez est introuvable ou n'existe plus.</p>
    <a href="/?page=home" class="btn btn-primary">Retour à l'accueil</a>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
