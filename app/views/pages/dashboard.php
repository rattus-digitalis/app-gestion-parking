<?php
$title = "Tableau de bord";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

// Protection : redirige si non connecté
if (!isset($_SESSION['user'])) {
    header('Location: /?page=login');
    exit;
}

$user = $_SESSION['user'];
?>

<main>
    <h1>Bienvenue <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name']) ?> 👋</h1>

    <p>Adresse e-mail : <?= htmlspecialchars($user['email']) ?></p>
    <p>Rôle : <?= htmlspecialchars($user['role']) ?></p>

    <p><a href="/?page=logout">Se déconnecter</a></p>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
