<!DOCTYPE html>
<html lang="fr" class="no-js">
<head>
    <!-- ✅ CSP - UNE SEULE FOIS ET AU BON ENDROIT -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://www.google-analytics.com https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self';">

    <!-- Métadonnées essentielles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    
    <!-- Headers de sécurité HTML -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    
    <!-- Titre dynamique avec fallback -->
    <title><?= htmlspecialchars($title ?? 'Parkly - Gestion de parking en ligne') ?></title>
    
    <!-- Métadonnées SEO -->
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Simplifiez la gestion de vos réservations de parking en ligne avec Parkly. Réservez, gérez et trouvez une place facilement.') ?>">
    <meta name="keywords" content="parking, réservation, gestion, place, stationnement<?= !empty($meta_keywords) ? ', ' . htmlspecialchars($meta_keywords) : '' ?>">
    <meta name="author" content="Parkly">
    <meta name="robots" content="<?= $meta_robots ?? 'index, follow' ?>">
    <link rel="canonical" href="<?= $canonical_url ?? 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($og_title ?? $title ?? 'Parkly') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($og_description ?? $meta_description ?? 'Gestion de parking en ligne simplifiée') ?>">
    <meta property="og:image" content="<?= $og_image ?? '/assets/images/og-image.jpg' ?>">
    <meta property="og:url" content="<?= $canonical_url ?? 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:site_name" content="Parkly">
    <meta property="og:locale" content="fr_FR">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($og_title ?? $title ?? 'Parkly') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($og_description ?? $meta_description ?? 'Gestion de parking en ligne simplifiée') ?>">
    <meta name="twitter:image" content="<?= $og_image ?? '/assets/images/og-image.jpg' ?>">

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/assets/images/android-chrome-512x512.png">

    <!-- Couleur du thème (mobile) -->
    <meta name="theme-color" content="#6366f1">
    <meta name="msapplication-TileColor" content="#6366f1">
    <meta name="msapplication-config" content="/assets/browserconfig.xml">

    <!-- Preconnect pour les performances -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts optimisées -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS principal avec versioning -->
    <?php
    $mainCss = '/assets/css/style.css';
    $mainCssPath = $_SERVER['DOCUMENT_ROOT'] . $mainCss;
    $mainCssVersion = file_exists($mainCssPath) ? filemtime($mainCssPath) : '1.0';
    ?>
    <link rel="stylesheet" href="<?= $mainCss ?>?v=<?= $mainCssVersion ?>">

    <!-- CSS de navigation avec versioning -->
    <?php
    $navCss = '/assets/css/templates/nav.css';
    $navCssPath = $_SERVER['DOCUMENT_ROOT'] . $navCss;
    $navCssVersion = file_exists($navCssPath) ? filemtime($navCssPath) : '1.0';
    ?>
    <link rel="stylesheet" href="<?= $navCss ?>?v=<?= $navCssVersion ?>">

    <!-- CSS composants réutilisés (header, footer, etc.) -->
    <?php if (!empty($component_css) && is_array($component_css)): ?>
        <?php foreach ($component_css as $css): ?>
            <?php if (is_array($css) && isset($css['href'])): ?>
                <link rel="stylesheet" href="<?= htmlspecialchars($css['href']) ?>?v=<?= $css['version'] ?? '1.0' ?>">
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- CSS spécifique par page -->
    <?php if (isset($page_css) && !empty($page_css)): ?>
        <?php
        $pageCssPath = $_SERVER['DOCUMENT_ROOT'] . $page_css;
        if (file_exists($pageCssPath)):
        ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($page_css) ?>?v=<?= filemtime($pageCssPath) ?>">
        <?php else: ?>
            <!-- ⚠️ CSS de page introuvable : <?= htmlspecialchars($page_css) ?> -->
        <?php endif; ?>
    <?php endif; ?>

    <!-- CSS critique inline (optionnel) -->
    <?php if (isset($critical_css) && !empty($critical_css)): ?>
        <style><?= $critical_css ?></style>
    <?php endif; ?>

    <!-- Preload des ressources critiques -->
    <?php if (isset($preload_resources) && is_array($preload_resources)): ?>
        <?php foreach ($preload_resources as $resource): ?>
            <?php if (is_array($resource) && isset($resource['href'], $resource['as'])): ?>
                <link rel="preload" 
                      href="<?= htmlspecialchars($resource['href']) ?>" 
                      as="<?= htmlspecialchars($resource['as']) ?>"
                      <?= isset($resource['type']) ? 'type="' . htmlspecialchars($resource['type']) . '"' : '' ?>
                      <?= isset($resource['crossorigin']) && $resource['crossorigin'] ? 'crossorigin' : '' ?>>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Variables CSS dynamiques -->
    <style>
        :root {
            --places-disponibles: <?= intval($places_libres ?? 0) ?>;
            --user-theme: '<?= htmlspecialchars($_SESSION['user_theme'] ?? 'auto') ?>';
            --current-page: '<?= htmlspecialchars($current_page ?? '') ?>';
        }
    </style>

    <!-- Scripts critiques inline -->
    <script>
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');

        window.parklyConfig = {
            apiUrl: <?= json_encode($_ENV['API_URL'] ?? '/api') ?>,
            debug: <?= json_encode(($_ENV['APP_ENV'] ?? 'production') === 'development') ?>,
            version: <?= json_encode($_ENV['APP_VERSION'] ?? '1.0.0') ?>,
            locale: 'fr',
            csrfToken: <?= json_encode($_SESSION['csrf_token'] ?? '') ?>,
            currentPage: <?= json_encode($current_page ?? '') ?>,
            isLoggedIn: <?= json_encode(isset($_SESSION['user'])) ?>,
            userRole: <?= json_encode($_SESSION['user']['role'] ?? null) ?>
        };

        if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        if (window.parklyConfig.debug) {
                            console.log('SW registered: ', registration);
                        }
                    })
                    .catch(function(registrationError) {
                        if (window.parklyConfig.debug) {
                            console.log('SW registration failed: ', registrationError);
                        }
                    });
            });
        }

        window.addEventListener('error', function(e) {
            if (window.parklyConfig.debug) {
                console.error('JavaScript Error:', e.error);
            }
        });

        if ('performance' in window && 'mark' in window.performance) {
            window.performance.mark('head-end');
        }

        if (window.matchMedia) {
            const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
            const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
            
            document.documentElement.classList.toggle('dark-mode', darkModeQuery.matches);
            document.documentElement.classList.toggle('reduced-motion', reducedMotionQuery.matches);
            
            darkModeQuery.addEventListener('change', (e) => {
                document.documentElement.classList.toggle('dark-mode', e.matches);
            });
        }
    </script>

    <!-- JSON-LD Schema pour le SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Parkly",
        "description": <?= json_encode($meta_description ?? 'Gestion de parking en ligne simplifiée') ?>,
        "url": <?= json_encode('https://' . $_SERVER['HTTP_HOST']) ?>,
        "potentialAction": {
            "@type": "SearchAction",
            "target": <?= json_encode('https://' . $_SERVER['HTTP_HOST'] . '/?page=search&q={search_term_string}') ?>,
            "query-input": "required name=search_term_string"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Parkly",
            "logo": {
                "@type": "ImageObject",
                "url": <?= json_encode('https://' . $_SERVER['HTTP_HOST'] . '/assets/images/logo.png') ?>
            }
        }
    }
    </script>

    <?php if (isset($page_schema) && !empty($page_schema)): ?>
        <script type="application/ld+json"><?= $page_schema ?></script>
    <?php endif; ?>

    <!-- DNS Prefetch pour les domaines externes -->
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">

    <!-- ✅ JS principal -->
    <?php
    $mainJs = '/js/app.js';
    $mainJsPath = $_SERVER['DOCUMENT_ROOT'] . $mainJs;
    if (file_exists($mainJsPath)) :
        $mainJsVersion = filemtime($mainJsPath);
    ?>
        <script type="module" src="<?= $mainJs ?>?v=<?= $mainJsVersion ?>"></script>
    <?php else: ?>
        <!-- ⚠️ JS principal introuvable : <?= $mainJs ?> -->
    <?php endif; ?>

</head>
<body data-page="<?= htmlspecialchars($current_page ?? 'unknown') ?>">