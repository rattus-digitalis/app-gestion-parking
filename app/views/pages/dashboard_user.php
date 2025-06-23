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

// Menu de navigation avec icônes et descriptions
$menu_items = [
    [
        'url' => '/?page=mon_compte',
        'title' => 'Mon compte',
        'description' => 'Gérer mes informations personnelles',
        'icon' => '👤'
    ],
    [
        'url' => '/?page=ma_voiture',
        'title' => 'Ma voiture',
        'description' => 'Consulter et modifier les informations de mon véhicule',
        'icon' => '🚗'
    ],
    [
        'url' => '/?page=mes_reservations',
        'title' => 'Mes réservations',
        'description' => 'Historique et gestion de mes réservations',
        'icon' => '📋'
    ],
    [
        'url' => '/?page=nouvelle_reservation',
        'title' => 'Nouvelle réservation',
        'description' => 'Réserver une place de parking',
        'icon' => '🅿️'
    ]
];

// Statistiques rapides (à adapter selon vos données)
$stats = [
    ['label' => 'Réservations actives', 'value' => '2', 'type' => 'success'],
    ['label' => 'Total réservations', 'value' => '15', 'type' => 'info'],
    ['label' => 'Places favorites', 'value' => '3', 'type' => 'primary']
];
?>

<main class="container fade-in" role="main">
    <!-- En-tête de bienvenue -->
    <header class="text-center mb-5">
        <h1 class="section-title">Espace Utilisateur</h1>
        
        <!-- Carte d'informations utilisateur -->
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <div class="user-welcome">
                <div class="user-avatar">
                    <span style="font-size: 3rem;">👋</span>
                </div>
                <div class="user-details">
                    <h2 class="mb-2">Bonjour <?= $prenom ?> <?= $nom ?></h2>
                    <?php if ($email): ?>
                        <p class="text-muted mb-0">Connecté avec : <?= $email ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="mt-4">
                <p class="text-secondary">
                    Bienvenue dans votre espace personnel. Gérez facilement vos réservations, 
                    vos informations de compte et votre véhicule depuis cette interface.
                </p>
            </div>
        </div>
    </header>

    <!-- Statistiques rapides -->
    <section class="stats-grid mb-5">
        <?php foreach ($stats as $stat): ?>
            <div class="stat-item fade-in">
                <span class="stat-number"><?= htmlspecialchars($stat['value']) ?></span>
                <span class="stat-label"><?= htmlspecialchars($stat['label']) ?></span>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Menu principal du tableau de bord -->
    <section aria-label="Menu principal">
        <h2 class="section-title">Que souhaitez-vous faire ?</h2>
        
        <div class="feature-grid">
            <?php foreach ($menu_items as $index => $item): ?>
                <div class="feature-card slide-in" style="animation-delay: <?= $index * 0.1 ?>s;">
                    <div class="feature-icon"><?= $item['icon'] ?></div>
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <p class="mb-4"><?= htmlspecialchars($item['description']) ?></p>
                    <a href="<?= htmlspecialchars($item['url']) ?>" 
                       class="btn btn-primary btn-lg" 
                       aria-label="<?= htmlspecialchars($item['title']) ?>">
                        Accéder
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Section d'informations rapides -->
    <aside class="mt-5" aria-label="Informations rapides">
        <h2 class="section-title">Informations de votre compte</h2>
        
        <div class="card">
            <div class="reservation-card">
                <ul>
                    <li>
                        <span><strong>Dernière connexion :</strong></span>
                        <span><?= date('d/m/Y à H:i') ?></span>
                    </li>
                    <li>
                        <span><strong>Statut du compte :</strong></span>
                        <span class="badge-success">
                            <span class="availability-indicator success"></span>
                            Actif
                        </span>
                    </li>
                    <li>
                        <span><strong>Membre depuis :</strong></span>
                        <span><?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                    </li>
                    <li>
                        <span><strong>Type de compte :</strong></span>
                        <span>Utilisateur standard</span>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <!-- Actions rapides -->
    <section class="mt-5">
        <h2 class="section-title">Actions rapides</h2>
        
        <div class="card text-center">
            <div class="reservation-actions" style="justify-content: center;">
                <a href="/?page=nouvelle_reservation" class="btn btn-primary btn-lg">
                    🅿️ Réserver maintenant
                </a>
                <a href="/?page=mes_reservations" class="btn btn-outline btn-lg">
                    📋 Voir mes réservations
                </a>
                <a href="/?page=mon_compte" class="btn btn-secondary">
                    ⚙️ Paramètres du compte
                </a>
            </div>
        </div>
    </section>

    <!-- Alerte d'information si nécessaire -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-info fade-in">
            <strong>Information :</strong> 
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>

    <!-- Footer d'aide -->
    <div class="card mt-5" style="background: var(--bg-tertiary); border-color: var(--primary-color);">
        <div class="text-center">
            <h3 style="color: var(--primary-light);">Besoin d'aide ?</h3>
            <p class="text-secondary mb-3">
                Consultez notre guide d'utilisation ou contactez notre support technique.
            </p>
            <div class="reservation-actions" style="justify-content: center;">
                <a href="/?page=aide" class="btn btn-info">
                    📖 Guide d'utilisation
                </a>
                <a href="/?page=contact" class="btn btn-outline">
                    💬 Contacter le support
                </a>
            </div>
        </div>
    </div>
</main>

<style>
/* Styles spécifiques au tableau de bord */
.user-welcome {
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
    text-align: left;
}

.user-avatar {
    flex-shrink: 0;
}

.user-details h2 {
    color: var(--text-accent);
    margin: 0;
}

.badge-success {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: var(--success-color);
    font-weight: 600;
}

/* Animation pour les cartes */
.feature-card {
    transform: translateY(10px);
    opacity: 0;
    animation: slideUpFade 0.6s ease-out forwards;
}

@keyframes slideUpFade {
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsive pour la section utilisateur */
@media (max-width: 768px) {
    .user-welcome {
        flex-direction: column;
        text-align: center;
        gap: var(--spacing-md);
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    }
}
</style>

<?php
// ========================================
// INCLUSION DU FOOTER
// ========================================
require_once __DIR__ . '/../templates/footer.php';
?>