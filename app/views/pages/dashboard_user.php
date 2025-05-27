<?php
$title = "Espace Utilisateur";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

$prenom = $_SESSION['user']['first_name'] ?? 'Utilisateur';
?>

<main class="container user-dashboard" role="main">
    <header>
        <h1> Espace Utilisateur</h1>
        <p>Bonjour <strong><?= htmlspecialchars($prenom) ?></strong>, ici vous pouvez gérer vos réservations et votre compte.</p>
    </header>

    <section class="dashboard-menu">
        <h2>Menu</h2>
        <ul class="menu-list">
            <li><a href="/?page=mon_compte" class="btn btn-dashboard"> Mon compte</a></li>
            <li><a href="/?page=ma_voiture" class="btn btn-dashboard"> Ma voiture</a></li>
            <li><a href="/?page=mes_reservations" class="btn btn-dashboard"> Mes réservations</a></li>
            <li><a href="/?page=nouvelle_reservation" class="btn btn-dashboard"> Nouvelle réservation</a></li>
        </ul>
    </section>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
