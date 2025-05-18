<?php
$title = "Espace Administrateur";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Panneau d'administration</h1>
    <p>Bonjour <?= htmlspecialchars($_SESSION['user']['first_name']) ?>, vous avez un accès complet.</p>

    <p><a href="/?page=admin_users">Gestion des utilisateurs</a></p>

    <p><a href="/?page=logout">Se déconnecter</a></p>
</main>


<?php require_once __DIR__ . '/../templates/footer.php'; ?>


