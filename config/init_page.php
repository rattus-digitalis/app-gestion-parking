<?php
// CSS principal
$mainCss = '/assets/css/style.css';
$mainCssPath = $_SERVER['DOCUMENT_ROOT'] . $mainCss;
$mainCssVersion = file_exists($mainCssPath) ? filemtime($mainCssPath) : '1.0';

// Injecte globalement
$GLOBALS['mainCss'] = $mainCss;
$GLOBALS['mainCssVersion'] = $mainCssVersion;

// CSS par page
$page_id = $_GET['page'] ?? 'home';
$page_css = "/assets/css/pages/{$page_id}.css";
$page_css_path = $_SERVER['DOCUMENT_ROOT'] . $page_css;
if (file_exists($page_css_path)) {
    $GLOBALS['page_css'] = $page_css;
    $GLOBALS['page_css_version'] = filemtime($page_css_path);
}

// CSS de composants toujours affichés (ex: nav)
$component_css = [
    '/assets/css/templates/nav.css',
];
$GLOBALS['component_css'] = [];
foreach ($component_css as $css) {
    $path = $_SERVER['DOCUMENT_ROOT'] . $css;
    if (file_exists($path)) {
        $GLOBALS['component_css'][] = [
            'href' => $css,
            'version' => filemtime($path)
        ];
    }
}
