<?php
/**
 * Page dashboard administration
 * Panneau de contrôle principal pour les administrateurs
 * 
 * @version 1.0.0
 * @author Admin System
 */

// ========================================
// CONFIGURATION ET SÉCURITÉ
// ========================================

// Configuration de la page
$title = "Dashboard Admin";
$page_description = "Panneau de contrôle administrateur pour la gestion du système";

// Vérification des droits d'administration
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /?page=login');
    exit;
}

// ========================================
// FONCTIONS UTILITAIRES
// ========================================

/**
 * Vérifier les permissions utilisateur
 * @param string $permission Permission à vérifier
 * @return bool True si autorisé
 */
function hasPermission($permission) {
    // Placeholder - à remplacer par votre logique de permissions
    return true;
}

/**
 * Sécuriser l'affichage des données utilisateur
 * @param mixed $data Données à sécuriser
 * @param string $default Valeur par défaut
 * @return string Données sécurisées
 */
function secureOutput($data, $default = '') {
    return htmlspecialchars($data ?? $default, ENT_QUOTES, 'UTF-8');
}

// ========================================
// DONNÉES UTILISATEUR
// ========================================

$admin = $_SESSION['user'];
$admin_data = [
    'prenom' => secureOutput($admin['first_name'], 'Admin'),
    'nom' => secureOutput($admin['last_name']),
    'email' => secureOutput($admin['email'])
];

// ========================================
// CONFIGURATION DES MENUS
// ========================================

// Menu principal d'administration avec icônes
$admin_menu_items = [
    [
        'url' => '/?page=admin_users',
        'title' => 'Gestion des utilisateurs',
        'description' => 'Créer, modifier et supprimer des comptes utilisateurs',
        'permission' => 'manage_users',
        'icon' => '👥',
        'color' => 'primary'
    ],
    [
        'url' => '/?page=admin_parkings',
        'title' => 'Gestion des places',
        'description' => 'Configuration et maintenance des espaces de stationnement',
        'permission' => 'manage_parkings',
        'icon' => '🅿️',
        'color' => 'info'
    ],
    [
        'url' => '/?page=reservations_list',
        'title' => 'Gestion des réservations',
        'description' => 'Consulter et modifier toutes les réservations',
        'permission' => 'manage_reservations',
        'icon' => '📋',
        'color' => 'success'
    ],
    [
        'url' => '/?page=admin_tarifs',
        'title' => 'Gestion des tarifs',
        'description' => 'Définir et ajuster les prix de stationnement',
        'permission' => 'manage_pricing',
        'icon' => '💰',
        'color' => 'warning'
    ],
];

// Actions rapides
$quick_actions = [
    [
        'url' => '/?page=admin_users&action=add',
        'title' => 'Ajouter un utilisateur',
        'icon' => '➕👤',
        'class' => 'btn-primary'
    ],
    [
        'url' => '/?page=admin_parkings&action=add',
        'title' => 'Ajouter une place',
        'icon' => '➕🅿️',
        'class' => 'btn-success'
    ],
    [
        'url' => '/?page=reservations_list&filter=today',
        'title' => 'Réservations du jour',
        'icon' => '📅',
        'class' => 'btn-info'
    ]
];

// Statistiques du système (à adapter selon vos données)
$system_stats = [
    ['label' => 'Utilisateurs actifs', 'value' => '127', 'trend' => '+12%'],
    ['label' => 'Places disponibles', 'value' => '43', 'trend' => '-5%'],
    ['label' => 'Réservations aujourd\'hui', 'value' => '28', 'trend' => '+8%'],
    ['label' => 'Revenus du mois', 'value' => '€2,450', 'trend' => '+15%']
];

// Informations système
$system_info = [
    'last_login' => date('d/m/Y à H:i'),
    'status' => 'Opérationnel',
    'status_class' => 'success',
    'version' => '1.0.0',
    'maintenance' => 'Aucune maintenance prévue',
    'uptime' => '99.9%',
    'backup' => 'Dernière sauvegarde : ' . date('d/m/Y à H:i', strtotime('-2 hours'))
];

// Alertes système (exemple)
$alerts = [
    [
        'type' => 'warning',
        'title' => 'Maintenance programmée',
        'message' => 'Une maintenance est prévue dimanche prochain de 02h00 à 04h00.',
        'show' => true
    ],
    [
        'type' => 'info',
        'title' => 'Nouvelle fonctionnalité',
        'message' => 'Le module de reporting avancé est maintenant disponible.',
        'show' => false
    ]
];

// ========================================
// INCLUSION DES TEMPLATES
// ========================================

require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container fade-in" role="main">
    
    <!-- ========================================
         EN-TÊTE ADMINISTRATEUR
         ======================================== -->
    <header class="text-center mb-5">
        <h1 class="section-title">Panneau d'Administration</h1>
        
        <!-- Carte de bienvenue admin -->
        <div class="card" style="max-width: 700px; margin: 0 auto;">
            <div class="user-welcome">
                <div class="user-avatar">
                    <span style="font-size: 3rem;">👨‍💼</span>
                </div>
                <div class="user-details">
                    <h2 class="mb-2">Bienvenue <?= $admin_data['prenom'] ?> <?= $admin_data['nom'] ?></h2>
                    <?php if (!empty($admin_data['email'])): ?>
                        <p class="text-muted mb-1">Connecté avec : <?= $admin_data['email'] ?></p>
                    <?php endif; ?>
                    <div class="badge-admin">
                        <span class="availability-indicator success"></span>
                        Accès administrateur complet
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <p class="text-secondary">
                    Vous disposez d'un accès complet aux fonctionnalités d'administration. 
                
                </p>
            </div>
        </div>
    </header>

    <!-- ========================================
         ALERTES SYSTÈME
         ======================================== -->
    <?php foreach ($alerts as $alert): ?>
        <?php if ($alert['show']): ?>
            <div class="alert alert-<?= $alert['type'] ?> fade-in">
                <strong><?= htmlspecialchars($alert['title']) ?> :</strong>
                <?= htmlspecialchars($alert['message']) ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- ========================================
         STATISTIQUES SYSTÈME
         ======================================== -->
    <section class="stats-grid mb-5">
        <?php foreach ($system_stats as $stat): ?>
            <div class="stat-item fade-in">
                <span class="stat-number"><?= htmlspecialchars($stat['value']) ?></span>
                <span class="stat-label"><?= htmlspecialchars($stat['label']) ?></span>
                <div class="stat-trend" style="color: <?= strpos($stat['trend'], '+') === 0 ? 'var(--success-color)' : 'var(--warning-color)' ?>;">
                    <?= htmlspecialchars($stat['trend']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- ========================================
         ACTIONS RAPIDES
         ======================================== -->
    <section class="mb-5">
        <h2 class="section-title">Actions rapides</h2>
        
        <div class="card">
            <div class="reservation-actions" style="justify-content: center;">
                <?php foreach ($quick_actions as $action): ?>
                    <a href="<?= secureOutput($action['url']) ?>" 
                       class="btn <?= $action['class'] ?> btn-lg">
                        <?= $action['icon'] ?> <?= secureOutput($action['title']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ========================================
         MENU PRINCIPAL D'ADMINISTRATION
         ======================================== -->
    <section aria-labelledby="admin-menu-title">
        <h2 id="admin-menu-title" class="section-title">Menu d'administration</h2>
        
        <div class="feature-grid">
            <?php foreach ($admin_menu_items as $index => $item): ?>
                <?php if (hasPermission($item['permission'])): ?>
                    <div class="feature-card slide-in" style="animation-delay: <?= $index * 0.1 ?>s;">
                        <div class="feature-icon"><?= $item['icon'] ?></div>
                        <h3><?= secureOutput($item['title']) ?></h3>
                        <p class="mb-4"><?= secureOutput($item['description']) ?></p>
                        <a href="<?= secureOutput($item['url']) ?>" 
                           class="btn btn-<?= $item['color'] ?> btn-lg"
                           aria-label="<?= secureOutput($item['title']) ?>">
                            Gérer
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ========================================
         INFORMATIONS SYSTÈME
         ======================================== -->
    <aside class="mt-5" aria-label="Informations système">
        <h2 class="section-title">État du système</h2>
        
        <div class="card">
            <div class="reservation-card">
                <ul>
                    <li>
                        <span><strong>Dernière connexion :</strong></span>
                        <span><?= $system_info['last_login'] ?></span>
                    </li>
                    <li>
                        <span><strong>Statut du système :</strong></span>
                        <span class="badge-<?= $system_info['status_class'] ?>">
                            <span class="availability-indicator <?= $system_info['status_class'] ?>"></span>
                            <?= $system_info['status'] ?>
                        </span>
                    </li>
                    <li>
                        <span><strong>Version :</strong></span>
                        <span><?= $system_info['version'] ?></span>
                    </li>
                    <li>
                        <span><strong>Temps de fonctionnement :</strong></span>
                        <span style="color: var(--success-color); font-weight: 600;"><?= $system_info['uptime'] ?></span>
                    </li>
                    <li>
                        <span><strong>Maintenance :</strong></span>
                        <span><?= $system_info['maintenance'] ?></span>
                    </li>
                    <li>
                        <span><strong>Sauvegarde :</strong></span>
                        <span><?= $system_info['backup'] ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    
    <!-- ========================================
         MONITORING EN TEMPS RÉEL
         ======================================== -->
    <section class="mt-5">
        <h2 class="section-title">Monitoring en temps réel</h2>
        
        <div class="availability-card">
            <div class="availability-info">
                <div class="availability-icon">📊</div>
                <div class="availability-text">
                    <div class="places-count">Système opérationnel</div>
                    <div class="occupation-rate">Surveillance active - Dernière mise à jour : <?= date('H:i:s') ?></div>
                </div>
            </div>
            <div class="availability-indicator success pulse"></div>
        </div>
        
        <!-- Barre de performance -->
        <div class="occupation-bar mt-3">
            <div class="occupation-fill" style="width: 85%; background: linear-gradient(90deg, var(--success-color), var(--info-color));"></div>
        </div>
        <div class="text-center mt-2">
            <small class="text-muted">Performance système : 85% - Excellent</small>
        </div>
    </section>

</main>

<style>
/* Styles spécifiques au dashboard admin */
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

.badge-admin {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: var(--primary-light);
    font-weight: 600;
    font-size: var(--font-size-sm);
}

.badge-success {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: var(--success-color);
    font-weight: 600;
}

.stat-trend {
    font-size: var(--font-size-xs);
    font-weight: 600;
    margin-top: var(--spacing-xs);
}

/* Animation pour les statistiques */
.stat-item {
    transform: translateY(10px);
    opacity: 0;
    animation: slideUpFade 0.6s ease-out forwards;
}

.stat-item:nth-child(1) { animation-delay: 0.1s; }
.stat-item:nth-child(2) { animation-delay: 0.2s; }
.stat-item:nth-child(3) { animation-delay: 0.3s; }
.stat-item:nth-child(4) { animation-delay: 0.4s; }

@keyframes slideUpFade {
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsive pour les sections admin */
@media (max-width: 768px) {
    .user-welcome {
        flex-direction: column;
        text-align: center;
        gap: var(--spacing-md);
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    }
    
    .reservation-actions {
        flex-direction: column;
    }
}
</style>

<?php
// ========================================
// INCLUSION DU FOOTER
// ========================================
require_once __DIR__ . '/../templates/footer.php';
?>