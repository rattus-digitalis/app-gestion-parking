<?php
$title = "Espace Utilisateur";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

$prenom = $_SESSION['user']['first_name'] ?? 'Utilisateur';
?>

<main>
    <h1>Bienvenue dans votre espace utilisateur</h1>
    <p>Bonjour <?= htmlspecialchars($prenom) ?>, ici vous pourrez gérer vos réservations.</p>

    <section class="dashboard-menu">
        <ul>
            <li><a href="/?page=mon_compte">Mon compte</a></li>
            <li><a href="/?page=ma_voiture">Ma voiture</a></li>
            <li><a href="/?page=mes_reservations">Mes réservations</a></li>
            <li><a href="/?page=nouvelle_reservation">Nouvelle réservation</a></li>
        </ul>
    </section>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
