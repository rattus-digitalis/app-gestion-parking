<?php
$title = "Espace Administrateur";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Panneau d'administration</h1>
    <p>Bonjour <?= htmlspecialchars($_SESSION['user']['first_name']) ?>, vous avez un accès complet.</p>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
