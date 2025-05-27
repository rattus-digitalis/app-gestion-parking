<?php
$title = "Dashboard Admin";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container admin-dashboard" role="main">
    <header class="admin-header">
        <h1>Panneau d’administration</h1>
        <p>
            Bonjour <strong><?= htmlspecialchars($_SESSION['user']['first_name'] ?? 'Admin') ?></strong>, vous avez un accès complet.
        </p>
    </header>

    <section aria-labelledby="admin-menu-title" class="admin-menu">
        <h2 id="admin-menu-title">Menu d’administration</h2>
        <ul class="admin-links">
            <li><a href="/?page=admin_users" class="btn-link" role="button">Gestion des utilisateurs</a></li>
            <li><a href="/?page=admin_parkings" class="btn-link" role="button">Gestion des places de parking</a></li>
            <li><a href="/?page=reservations_list" class="btn-link" role="button">Gestion des réservations</a></li>
            <li><a href="/?page=admin_tarifs" class="btn-link" role="button"> Gestion des tarifs</a></li>
        </ul>
    </section>

    <footer class="admin-footer">
        <a href="/?page=logout" class="btn-link logout" role="button" aria-label="Se déconnecter">🚪 Se déconnecter</a>
    </footer>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
