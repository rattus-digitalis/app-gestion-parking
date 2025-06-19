<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$current_page = $_GET['page'] ?? 'home';
$user = $_SESSION['user'] ?? null;
$is_logged_in = !empty($user);
$is_admin = $is_logged_in && (isset($user['role']) && strtolower($user['role']) === 'admin');

$nav_items = [
    'public' => [
        ['url' => '/?page=home', 'label' => 'Accueil', 'icon' => 'home', 'key' => 'home']
    ],
    'guest' => [
        ['url' => '/?page=login', 'label' => 'Connexion', 'icon' => 'log-in', 'key' => 'login'],
        ['url' => '/?page=register', 'label' => 'Créer un compte', 'icon' => 'user-plus', 'key' => 'register']
    ],
    'dashboard' => [
        ['url' => '/?page=dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard', 'key' => 'dashboard']
    ],
    'user' => [
        ['url' => '/?page=nouvelle_reservation', 'label' => 'Réserver', 'icon' => 'calendar-plus', 'key' => 'nouvelle_reservation'],
        ['url' => '/?page=mes_reservations', 'label' => 'Mes réservations', 'icon' => 'calendar', 'key' => 'mes_reservations']
    ],
    'logout' => [
        ['url' => '/?page=logout', 'label' => 'Déconnexion', 'icon' => 'log-out', 'key' => 'logout', 'class' => 'nav-link--logout']
    ]
];

// Plus de lien 'admin'
function getNavigationItems(array $nav_items, bool $is_logged_in, bool $is_admin): array {
    $items = $nav_items['public'];

    if ($is_logged_in) {
        $items = array_merge($items, $nav_items['dashboard']);

        if (!$is_admin) {
            $items = array_merge($items, $nav_items['user']);
        }

        $items = array_merge($items, $nav_items['logout']);
    } else {
        $items = array_merge($items, $nav_items['guest']);
    }

    return $items;
}

function isActiveNavItem(string $item_key, string $current_page): bool {
    $special = [
        'dashboard' => ['dashboard', 'dashboard_user', 'dashboard_admin'],
        'home' => ['home', '']
    ];
    return isset($special[$item_key])
        ? in_array($current_page, $special[$item_key])
        : $item_key === $current_page;
}

function renderNavItem(array $item, string $current_page): string {
    $is_active = isActiveNavItem($item['key'], $current_page);
    $classes = ['nav-link'];
    if ($is_active) $classes[] = 'nav-link--active';
    if (!empty($item['class'])) $classes[] = $item['class'];
    $class_attr = implode(' ', $classes);
    $aria = $is_active ? ' aria-current="page"' : '';
    $icon = !empty($item['icon']) ? '<span class="nav-icon" data-icon="' . htmlspecialchars($item['icon']) . '" aria-hidden="true"></span>' : '';
    return sprintf(
        '<li class="nav-item"><a href="%s" class="%s"%s>%s<span class="nav-text">%s</span></a></li>',
        htmlspecialchars($item['url']),
        htmlspecialchars($class_attr),
        $aria,
        $icon,
        htmlspecialchars($item['label'])
    );
}

$navigation_items = getNavigationItems($nav_items, $is_logged_in, $is_admin);
?>

<nav class="site-nav" role="navigation" aria-label="Menu principal">
    <div class="nav-container">
        <div class="nav-brand">
            <a href="/?page=home" class="brand-link" aria-label="Retour à l'accueil">
                <span class="brand-text">Parkly</span>
            </a>
        </div>

        <ul class="nav-list" role="menubar">
            <?php foreach ($navigation_items as $item): ?>
                <?= renderNavItem($item, $current_page) ?>
            <?php endforeach; ?>
        </ul>

        <?php if ($is_logged_in): ?>
            <div class="nav-user" role="region" aria-label="Informations utilisateur">
                <span class="user-welcome">
                    <span class="user-icon" data-icon="user" aria-hidden="true"></span>
                    <span class="user-name">Bonjour, <?= htmlspecialchars($user['prenom'] ?? $user['nom'] ?? 'Utilisateur') ?></span>
                    <?php if ($is_admin): ?>
                        <span class="user-badge user-badge--admin" title="Administrateur">Admin</span>
                    <?php endif; ?>
                </span>
            </div>
        <?php endif; ?>

        <button class="nav-toggle"
                type="button"
                aria-expanded="false"
                aria-controls="nav-menu"
                aria-label="Ouvrir le menu">
            <span class="nav-toggle-icon"></span>
        </button>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const navToggle = document.querySelector('.nav-toggle');
    const navList = document.querySelector('.nav-list');
    if (navToggle && navList) {
        navToggle.addEventListener('click', () => {
            const isOpen = navList.classList.contains('nav-list--open');
            navList.classList.toggle('nav-list--open');
            navToggle.setAttribute('aria-expanded', String(!isOpen));
            navToggle.setAttribute('aria-label', isOpen ? 'Ouvrir le menu' : 'Fermer le menu');
        });
        navList.addEventListener('click', function (e) {
            if (e.target.classList.contains('nav-link')) {
                navList.classList.remove('nav-list--open');
                navToggle.setAttribute('aria-expanded', 'false');
                navToggle.setAttribute('aria-label', 'Ouvrir le menu');
            }
        });
        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) {
                navList.classList.remove('nav-list--open');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
</script>
