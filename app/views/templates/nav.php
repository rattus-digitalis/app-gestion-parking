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
        ['url' => '/?page=dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard', 'key' => 'dashboard']
    ],
    'user' => [
        ['url' => '/?page=nouvelle_reservation', 'label' => 'Réserver', 'icon' => 'calendar-plus', 'key' => 'nouvelle_reservation'],
        ['url' => '/?page=mes_reservations', 'label' => 'Mes réservations', 'icon' => 'calendar', 'key' => 'mes_reservations']
    ],
    'logout' => [
        ['url' => '/?page=logout', 'label' => 'Déconnexion', 'icon' => 'log-out', 'key' => 'logout', 'class' => 'nav-link--logout']
    ]
];

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
        '<li class="nav-item">
            <a href="%s" class="%s"%s>
                %s
                <span class="nav-text">%s</span>
                <span class="nav-highlight" aria-hidden="true"></span>
            </a>
        </li>',
        htmlspecialchars($item['url']),
        htmlspecialchars($class_attr),
        $aria,
        $icon,
        htmlspecialchars($item['label'])
    );
}

$navigation_items = getNavigationItems($nav_items, $is_logged_in, $is_admin);
?>

<nav class="site-nav fade-in" role="navigation" aria-label="Menu principal">
    <div class="nav-container">
        <!-- Logo/Brand -->
        <div class="nav-brand">
            <a href="/?page=home" class="brand-link" aria-label="Retour à l'accueil">
                <div class="brand-logo">
                    <span class="brand-icon" data-icon="parking-circle" aria-hidden="true"></span>
                </div>
                <span class="brand-text">Parkly</span>
                <span class="brand-subtitle">Parking Management</span>
            </a>
        </div>

        <!-- Navigation principale -->
        <ul class="nav-list" role="menubar" id="nav-menu">
            <?php foreach ($navigation_items as $item): ?>
                <?= renderNavItem($item, $current_page) ?>
            <?php endforeach; ?>
        </ul>

        <!-- Informations utilisateur -->
        <?php if ($is_logged_in): ?>
            <div class="nav-user slide-in" role="region" aria-label="Informations utilisateur">
                <div class="user-info">
                    <div class="user-avatar">
                        <span class="user-avatar-icon" data-icon="user" aria-hidden="true"></span>
                    </div>
                    <div class="user-details">
                        <span class="user-welcome">
                            Bonjour, <strong><?= htmlspecialchars($user['prenom'] ?? $user['nom'] ?? 'Utilisateur') ?></strong>
                        </span>
                        <?php if ($is_admin): ?>
                        <?php else: ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Bouton menu mobile -->
        <button class="nav-toggle"
                type="button"
                aria-expanded="false"
                aria-controls="nav-menu"
                aria-label="Ouvrir le menu">
            <span class="nav-toggle-bar"></span>
            <span class="nav-toggle-bar"></span>
            <span class="nav-toggle-bar"></span>
        </button>
    </div>

    <!-- Overlay pour mobile -->
    <div class="nav-overlay" aria-hidden="true"></div>
</nav>

<style>
/* ===== NAVIGATION STYLES ===== */
.site-nav {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border-color);
    box-shadow: var(--shadow-md);
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 var(--spacing-lg);
    min-height: 4rem;
    position: relative;
}

/* ===== BRAND/LOGO ===== */
.nav-brand {
    display: flex;
    align-items: center;
    z-index: 1001;
}

.brand-link {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    text-decoration: none;
    color: var(--text-primary);
    transition: all var(--transition-fast);
    padding: var(--spacing-sm);
    border-radius: var(--radius-md);
}

.brand-link:hover {
    background: rgba(99, 102, 241, 0.1);
    transform: translateY(-1px);
}

.brand-logo {
    position: relative;
    width: 2.5rem;
    height: 2.5rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-md);
}

.brand-icon {
    font-size: 1.25rem;
    color: white;
}

.brand-text {
    font-size: var(--font-size-xl);
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.brand-subtitle {
    font-size: var(--font-size-xs);
    color: var(--text-muted);
    font-weight: 400;
    margin-left: -0.5rem;
    margin-top: 0.25rem;
    align-self: flex-end;
}

/* ===== NAVIGATION LIST ===== */
.nav-list {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-item {
    position: relative;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-md);
    text-decoration: none;
    color: var(--text-secondary);
    font-weight: 500;
    font-size: var(--font-size-sm);
    transition: all var(--transition-fast);
    position: relative;
    overflow: hidden;
    white-space: nowrap;
}

.nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
    transition: left var(--transition-normal);
}

.nav-link:hover::before {
    left: 100%;
}

.nav-link:hover {
    color: var(--text-primary);
    background: rgba(99, 102, 241, 0.1);
    transform: translateY(-1px);
}

.nav-link--active {
    color: var(--primary-light);
    background: rgba(99, 102, 241, 0.15);
    border: 1px solid rgba(99, 102, 241, 0.3);
}

.nav-link--active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 50%;
    transform: translateX(-50%);
    width: 2rem;
    height: 2px;
    background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
    border-radius: 1px;
}

.nav-link--logout {
    color: var(--danger-color);
}

.nav-link--logout:hover {
    color: white;
    background: rgba(239, 68, 68, 0.1);
    border-color: var(--danger-color);
}


.nav-icon {
    font-size: 1rem;
    flex-shrink: 0;
}

.nav-text {
    font-weight: 600;
}

.nav-highlight {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    opacity: 0;
    transition: opacity var(--transition-fast);
    border-radius: inherit;
    z-index: -1;
}

.nav-link--active .nav-highlight {
    opacity: 0.1;
}

/* ===== USER INFO ===== */
.nav-user {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-sm);
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
}

.user-info {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.user-avatar {
    width: 2rem;
    height: 2rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.user-avatar-icon {
    font-size: 0.875rem;
    color: white;
}

.user-details {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.user-welcome {
    font-size: var(--font-size-sm);
    color: var(--text-primary);
    line-height: 1.2;
}

.user-welcome strong {
    color: var(--primary-light);
}

.user-role {
    font-size: var(--font-size-xs);
    color: var(--text-muted);
}

.user-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.125rem 0.5rem;
    border-radius: var(--radius-sm);
    font-size: var(--font-size-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.user-badge--admin {
    background: linear-gradient(135deg, var(--success-color), var(--success-hover));
    color: white;
    box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
}

.badge-icon {
    font-size: 0.75rem;
}

/* ===== MOBILE TOGGLE ===== */
.nav-toggle {
    display: none;
    flex-direction: column;
    justify-content: space-around;
    width: 2rem;
    height: 2rem;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
    z-index: 1001;
    border-radius: var(--radius-sm);
    transition: all var(--transition-fast);
}

.nav-toggle:hover {
    background: rgba(99, 102, 241, 0.1);
}

.nav-toggle-bar {
    width: 1.5rem;
    height: 2px;
    background: var(--text-primary);
    border-radius: 1px;
    transition: all var(--transition-fast);
    transform-origin: center;
}

.nav-toggle[aria-expanded="true"] .nav-toggle-bar:nth-child(1) {
    transform: rotate(45deg) translate(0.375rem, 0.375rem);
}

.nav-toggle[aria-expanded="true"] .nav-toggle-bar:nth-child(2) {
    opacity: 0;
}

.nav-toggle[aria-expanded="true"] .nav-toggle-bar:nth-child(3) {
    transform: rotate(-45deg) translate(0.375rem, -0.375rem);
}

/* ===== OVERLAY ===== */
.nav-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    opacity: 0;
    visibility: hidden;
    transition: all var(--transition-normal);
    z-index: 999;
}

.nav-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .nav-container {
        padding: 0 var(--spacing-md);
    }

    .nav-toggle {
        display: flex;
    }

    .brand-subtitle {
        display: none;
    }

    .nav-list {
        position: fixed;
        top: 0;
        right: -100%;
        width: 280px;
        height: 100vh;
        background: var(--bg-secondary);
        flex-direction: column;
        align-items: stretch;
        gap: 0;
        padding: 5rem var(--spacing-lg) var(--spacing-lg);
        border-left: 1px solid var(--border-color);
        box-shadow: var(--shadow-xl);
        transition: right var(--transition-normal);
        overflow-y: auto;
    }

    .nav-list.nav-list--open {
        right: 0;
    }

    .nav-item {
        margin-bottom: var(--spacing-sm);
    }

    .nav-link {
        padding: var(--spacing-md);
        border-radius: var(--radius-md);
        justify-content: flex-start;
    }

    .nav-link--active::after {
        display: none;
    }

    .nav-user {
        position: fixed;
        top: var(--spacing-md);
        left: var(--spacing-md);
        right: 5rem;
        z-index: 1000;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-lg);
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-welcome {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
}

@media (max-width: 480px) {
    .nav-container {
        padding: 0 var(--spacing-sm);
        min-height: 3.5rem;
    }

    .brand-logo {
        width: 2rem;
        height: 2rem;
    }

    .brand-text {
        font-size: var(--font-size-lg);
    }

    .nav-list {
        width: 100%;
        right: -100%;
        padding: 4rem var(--spacing-md) var(--spacing-md);
    }

    .nav-user {
        left: var(--spacing-sm);
        right: 4rem;
        padding: var(--spacing-sm);
    }

    .user-avatar {
        width: 1.5rem;
        height: 1.5rem;
    }

    .user-welcome {
        font-size: var(--font-size-xs);
    }
}

/* ===== PRINT STYLES ===== */
@media print {
    .site-nav {
        display: none;
    }
}

/* ===== FOCUS STYLES ===== */
.nav-link:focus,
.brand-link:focus,
.nav-toggle:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

/* ===== ANIMATIONS ===== */
.nav-item {
    animation: slideIn 0.4s ease-out;
    animation-fill-mode: both;
}

.nav-item:nth-child(1) { animation-delay: 0.1s; }
.nav-item:nth-child(2) { animation-delay: 0.2s; }
.nav-item:nth-child(3) { animation-delay: 0.3s; }
.nav-item:nth-child(4) { animation-delay: 0.4s; }
.nav-item:nth-child(5) { animation-delay: 0.5s; }

@media (prefers-reduced-motion: reduce) {
    .nav-item {
        animation: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const navToggle = document.querySelector('.nav-toggle');
    const navList = document.querySelector('.nav-list');
    const navOverlay = document.querySelector('.nav-overlay');
    const body = document.body;

    if (navToggle && navList && navOverlay) {
        // Toggle mobile menu
        navToggle.addEventListener('click', function() {
            const isOpen = navList.classList.contains('nav-list--open');
            
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        // Close menu when clicking on overlay
        navOverlay.addEventListener('click', closeMenu);

        // Close menu when clicking on a nav link
        navList.addEventListener('click', function(e) {
            if (e.target.classList.contains('nav-link')) {
                closeMenu();
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navList.classList.contains('nav-list--open')) {
                closeMenu();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMenu();
            }
        });

        function openMenu() {
            navList.classList.add('nav-list--open');
            navOverlay.classList.add('active');
            navToggle.setAttribute('aria-expanded', 'true');
            navToggle.setAttribute('aria-label', 'Fermer le menu');
            body.style.overflow = 'hidden';
            
            // Focus trap
            const firstFocusable = navList.querySelector('.nav-link');
            if (firstFocusable) {
                firstFocusable.focus();
            }
        }

        function closeMenu() {
            navList.classList.remove('nav-list--open');
            navOverlay.classList.remove('active');
            navToggle.setAttribute('aria-expanded', 'false');
            navToggle.setAttribute('aria-label', 'Ouvrir le menu');
            body.style.overflow = '';
        }
    }

    // Add scroll effect to navigation
    let lastScrollY = window.scrollY;
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('.site-nav');
        const currentScrollY = window.scrollY;
        
        if (nav) {
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                nav.style.transform = 'translateY(-100%)';
            } else {
                nav.style.transform = 'translateY(0)';
            }
            
            // Add/remove backdrop blur based on scroll
            if (currentScrollY > 10) {
                nav.classList.add('nav-scrolled');
            } else {
                nav.classList.remove('nav-scrolled');
            }
        }
        
        lastScrollY = currentScrollY;
    });

    // Add additional scroll styles
    const additionalStyles = `
        .site-nav {
            transition: transform var(--transition-normal);
        }
        
        .site-nav.nav-scrolled {
            background: rgba(15, 23, 42, 0.98);
            box-shadow: var(--shadow-lg);
        }
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = additionalStyles;
    document.head.appendChild(styleSheet);
});
</script>