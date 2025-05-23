<?php
$title = "Erreur 401 - Accès refusé";
include __DIR__ . '/../templates/head.php';
?>

<main style="text-align: center; padding: 50px;">
    <h1 style="font-size: 3rem; color: #ff5555;">Erreur 401</h1>
    <p style="font-size: 1.2rem; margin-bottom: 30px;">Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
    <a href="/?page=home" style="background-color: #7aa2f7; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Retour à l'accueil</a>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>

