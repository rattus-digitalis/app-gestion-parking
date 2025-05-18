<?php
$title = "Espace Utilisateur";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Bienvenue dans votre espace utilisateur</h1>
    <p>Bonjour <?= htmlspecialchars($_SESSION['user']['first_name']) ?>, ici vous pourrez gérer vos réservations.</p>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
