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

// Menu principal d'administration
$admin_menu_items = [
    [
        'url' => '/?page=admin_users',
        'title' => 'Gestion des utilisateurs',
        'description' => 'Créer, modifier et supprimer des comptes utilisateurs',
        'permission' => 'manage_users'
    ],
    [
        'url' => '/?page=admin_parkings',
        'title' => 'Gestion des places de parking',
        'description' => 'Configuration et maintenance des espaces de stationnement',
        'permission' => 'manage_parkings'
    ],
    [
        'url' => '/?page=reservations_list',
        'title' => 'Gestion des réservations',
        'description' => 'Consulter et modifier toutes les réservations',
        'permission' => 'manage_reservations'
    ],
    [
        'url' => '/?page=admin_tarifs',
        'title' => 'Gestion des tarifs',
        'description' => 'Définir et ajuster les prix de stationnement',
        'permission' => 'manage_pricing'
    ],
    [
        'url' => '/?page=admin_reports',
        'title' => 'Rapports et statistiques',
        'description' => 'Consulter les analyses et données du système',
        'permission' => 'view_reports'
    ]
];

// Actions rapides
$quick_actions = [
    [
        'url' => '/?page=admin_users&action=add',
        'title' => 'Ajouter un utilisateur',
        'class' => 'btn-quick-action'
    ],
    [
        'url' => '/?page=admin_parkings&action=add',
        'title' => 'Ajouter une place',
        'class' => 'btn-quick-action'
    ],
    [
        'url' => '/?page=reservations_list&filter=today',
        'title' => 'Réservations du jour',
        'class' => 'btn-quick-action'
    ]
];

// Informations système
$system_info = [
    'last_login' => date('d/m/Y à H:i'),
    'status' => 'Opérationnel',
    'status_class' => 'status-operational',
    'version' => '1.0.0',
    'maintenance' => 'Aucune maintenance prévue'
];

// ========================================
// INCLUSION DES TEMPLATES
// ========================================

require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container admin-dashboard" role="main">
    
    <!-- ========================================
         EN-TÊTE ADMINISTRATEUR
         ======================================== -->
    <header class="admin-header">
        <div class="admin-welcome">
            <h1>Panneau d'administration</h1>
            <div class="admin-info">
                <p>Bonjour <strong><?= $admin_data['prenom'] ?> <?= $admin_data['nom'] ?></strong></p>
                <?php if (!empty($admin_data['email'])): ?>
                    <p class="admin-email">Connecté avec : <?= $admin_data['email'] ?></p>
                <?php endif; ?>
                <p class="admin-role">Accès administrateur complet</p>
            </div>
        </div>
        
        <div class="admin-summary">
            <p>Vous disposez d'un accès complet aux fonctionnalités d'administration.</p>
        </div>
    </header>
    <!-- ========================================
         MENU PRINCIPAL D'ADMINISTRATION
         ======================================== -->
    <section aria-labelledby="admin-menu-title" class="admin-menu">
        <h2 id="admin-menu-title">Menu d'administration</h2>
        
        <div class="admin-menu-grid">
            <?php foreach ($admin_menu_items as $item): ?>
                <?php if (hasPermission($item['permission'])): ?>
                    <div class="admin-menu-item">
                        <a href="<?= secureOutput($item['url']) ?>" 
                           class="admin-menu-link"
                           aria-label="<?= secureOutput($item['title']) ?>"
                           title="<?= secureOutput($item['description']) ?>">
                            <div class="menu-item-content">
                                <h3><?= secureOutput($item['title']) ?></h3>
                                <p><?= secureOutput($item['description']) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ========================================
         INFORMATIONS SYSTÈME
         ======================================== -->
    <aside class="system-info" aria-label="Informations système">
        <h2>Informations système</h2>
        
        <div class="info-grid">
            <div class="info-item">
                <strong>Dernière connexion :</strong>
                <span><?= $system_info['last_login'] ?></span>
            </div>
            <div class="info-item">
                <strong>Statut du système :</strong>
                <span class="<?= $system_info['status_class'] ?>"><?= $system_info['status'] ?></span>
            </div>
            <div class="info-item">
                <strong>Version :</strong>
                <span><?= $system_info['version'] ?></span>
            </div>
            <div class="info-item">
                <strong>Maintenance :</strong>
                <span><?= $system_info['maintenance'] ?></span>
            </div>
        </div>
    </aside>

</main>

<?php
// ========================================
// INCLUSION DU FOOTER
// ========================================
require_once __DIR__ . '/../templates/footer.php';
?>