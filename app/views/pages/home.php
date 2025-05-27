<?php
declare(strict_types=1);

// ───────────── CONFIGURATION ─────────────

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$title = "Accueil";
$page_id = "home";

// ───────────── CONNEXION BDD ─────────────

require_once __DIR__ . '/../../../config/config.php';

if (!isset($pdo) || !$pdo instanceof PDO) {
    http_response_code(500);
    die("Connexion à la base de données échouée.");
}

// ───────────── REQUÊTE PLACES ─────────────

try {
    $stmt = $pdo->query("
        SELECT COUNT(*) AS libres 
        FROM parking 
        WHERE statut = 'libre' AND actif = 1
    ");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $places_libres = is_numeric($result['libres']) ? (int) $result['libres'] : 0;
} catch (PDOException $e) {
    http_response_code(500);
    die("Erreur SQL : " . $e->getMessage());
}

// ───────────── TEMPLATES ─────────────

require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container home-main" role="main">

    <!-- HÉRO -->
    <section class="hero" aria-labelledby="hero-title">
        <header class="hero-header">
            <h1 id="hero-title">Bienvenue sur <strong>Zenpark</strong></h1>
            <p class="subtitle">
                Simplifiez la gestion de vos <strong>réservations</strong> de parking en ligne.
            </p>
        </header>

        <div class="availability-info" role="status" aria-live="polite">
            🚙 <strong><?= $places_libres ?></strong>
            place<?= $places_libres > 1 ? 's' : '' ?> disponible<?= $places_libres > 1 ? 's' : '' ?> actuellement.
        </div>

        <nav class="cta home-buttons" role="navigation" aria-label="Actions principales">
            <a href="/?page=login" class="btn btn-primary" aria-label="Connexion utilisateur">Connexion</a>
            <a href="/?page=register" class="btn btn-secondary" aria-label="Créer un compte Zenpark">Créer un compte</a>
        </nav>
    </section>

    <!-- FONCTIONNALITÉS -->
    <section class="features" aria-label="Avantages Zenpark">
        <h2 class="visually-hidden">Fonctionnalités principales</h2>
        <ul class="feature-list">
            <li class="feature"><strong>Réservez</strong> une place en quelques clics</li>
            <li class="feature"><strong>Gérez</strong> vos réservations en toute autonomie</li>
            <li class="feature"><strong>Trouvez</strong> un parking proche de chez vous</li>
        </ul>
    </section>

</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
