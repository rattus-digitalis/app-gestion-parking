<?php
$title = "Dashboard Admin";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Panneau d'administration</h1>
    <p>Bonjour <?= htmlspecialchars($_SESSION['user']['first_name']) ?>, vous avez un accès complet.</p>

    <nav aria-label="Menu d'administration">
        <ul>
            <li><a href="/?page=admin_users" class="btn-link">Gestion des utilisateurs</a></li>
            <li><a href="/?page=admin_parkings" class="btn-link">Gestion des places de parking</a></li>
            <li><a href="/?page=reservations_list" class="btn-link">Gestion des réservations</a></li>
            <!-- Ajoute d'autres liens au besoin -->
        </ul>
    </nav>

    <p><a href="/?page=logout" class="btn-link">Se déconnecter</a></p>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
