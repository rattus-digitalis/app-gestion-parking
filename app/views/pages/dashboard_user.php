<?php
/**
 * Page tableau de bord utilisateur
 * Affiche le menu principal de l'espace utilisateur avec navigation
 */

// Configuration de la page
$title = "Espace Utilisateur";
$page_description = "Gérez vos réservations et votre compte utilisateur";

// Vérification de la session utilisateur
if (!isset($_SESSION['user'])) {
    header('Location: /?page=login');
    exit;
}

// Inclusion des templates
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

// Récupération des données utilisateur
$user = $_SESSION['user'];
$prenom = htmlspecialchars($user['first_name'] ?? 'Utilisateur');
$nom = htmlspecialchars($user['last_name'] ?? '');
$email = htmlspecialchars($user['email'] ?? '');

// Menu de navigation avec descriptions
$menu_items = [
    [
        'url' => '/?page=mon_compte',
        'title' => 'Mon compte',
        'description' => 'Gérer mes informations personnelles'
    ],
    [
        'url' => '/?page=ma_voiture',
        'title' => 'Ma voiture',
        'description' => 'Consulter et modifier les informations de mon véhicule'
    ],
    [
        'url' => '/?page=mes_reservations',
        'title' => 'Mes réservations',
        'description' => 'Historique et gestion de mes réservations'
    ],
    [
        'url' => '/?page=nouvelle_reservation',
        'title' => 'Nouvelle réservation',
        'description' => 'Réserver une place de parking'
    ]
];
?>

<main class="container user-dashboard" role="main">
    <!-- En-tête de bienvenue -->
    <header class="dashboard-header">
        <h1>Espace Utilisateur</h1>
        <div class="user-info">
            <p>Bonjour <strong><?= $prenom ?> <?= $nom ?></strong></p>
            <?php if ($email): ?>
                <p class="user-email">Connecté avec : <?= $email ?></p>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-summary">
            <p>Bienvenue dans votre espace personnel. Ici vous pouvez gérer vos réservations, vos informations de compte et votre véhicule.</p>
        </div>
    </header>

    <!-- Menu principal du tableau de bord -->
    <section class="dashboard-menu" aria-label="Menu principal">
        <h2>Que souhaitez-vous faire ?</h2>
        
        <div class="menu-list">
            <?php foreach ($menu_items as $item): ?>
                <div class="menu-item">
                    <a href="<?= htmlspecialchars($item['url']) ?>" 
                       class="btn btn-dashboard-large"
                       aria-label="<?= htmlspecialchars($item['title']) ?>">
                        <div class="menu-content">
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p><?= htmlspecialchars($item['description']) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Section d'informations rapides -->
    <aside class="quick-info" aria-label="Informations rapides">
        <h2>Informations de votre compte</h2>
        <div class="info-list">
            <div class="info-item">
                <strong>Dernière connexion :</strong> <?= date('d/m/Y à H:i') ?>
            </div>
            <div class="info-item">
                <strong>Statut du compte :</strong> <span class="status-active">Actif</span>
            </div>
        </div>
    </aside>
</main>