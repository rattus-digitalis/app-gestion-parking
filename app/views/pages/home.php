<?php
declare(strict_types=1);

// ───────────── CONFIGURATION ─────────────

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$title = "Accueil - Parkly";
$page_id = "home";
$meta_description = "Simplifiez la gestion de vos réservations de parking en ligne avec Parkly. Réservez votre place en quelques clics.";

// ───────────── CONNEXION BDD ─────────────

require_once __DIR__ . '/../../../config/config.php';

if (!isset($pdo) || !$pdo instanceof PDO) {
    error_log("Erreur de connexion à la base de données");
    http_response_code(500);
    die("Une erreur technique est survenue. Veuillez réessayer plus tard.");
}

// ───────────── FONCTIONS UTILITAIRES ─────────────

/**
 * Formate le nombre de places avec accord pluriel
 */
function formatPlacesLibres(int $count): string {
    if ($count === 0) {
        return "Aucune place disponible";
    }
    
    $pluriel = $count > 1 ? 's' : '';
    return "{$count} place{$pluriel} disponible{$pluriel}";
}

/**
 * Récupère les statistiques du parking
 */
function getStatistiquesParking(PDO $pdo): array {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                COUNT(CASE WHEN statut = 'libre' AND actif = 1 THEN 1 END) AS libres,
                COUNT(CASE WHEN statut = 'occupe' AND actif = 1 THEN 1 END) AS occupees,
                COUNT(CASE WHEN actif = 1 THEN 1 END) AS total
            FROM parking
        ");
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'libres' => (int) ($result['libres'] ?? 0),
            'occupees' => (int) ($result['occupees'] ?? 0),
            'total' => (int) ($result['total'] ?? 0)
        ];
    } catch (PDOException $e) {
        error_log("Erreur SQL lors de la récupération des statistiques : " . $e->getMessage());
        return ['libres' => 0, 'occupees' => 0, 'total' => 0];
    }
}

// ───────────── RÉCUPÉRATION DES DONNÉES ─────────────

$stats = getStatistiquesParking($pdo);
$places_libres = $stats['libres'];
$taux_occupation = $stats['total'] > 0 ? round(($stats['occupees'] / $stats['total']) * 100, 1) : 0;

// ───────────── TEMPLATES ─────────────

require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container home-main" role="main">

    <!-- HÉRO -->
    <section class="hero" aria-labelledby="hero-title">
        <header class="hero-header">
            <h1 id="hero-title">Bienvenue sur <strong>Parkly</strong></h1>
            <p class="subtitle">
                Simplifiez la gestion de vos <strong>réservations</strong> de parking en ligne.
            </p>
        </header>

        <!-- INFORMATIONS DE DISPONIBILITÉ -->
        <div class="availability-card" role="status" aria-live="polite">
            <div class="availability-info">
                <span class="availability-icon" aria-hidden="true">🚗</span>
                <div class="availability-text">
                    <strong class="places-count"><?= formatPlacesLibres($places_libres) ?></strong>
                    <?php if ($stats['total'] > 0): ?>
                        <small class="occupation-rate">
                            Taux d'occupation : <?= $taux_occupation ?>%
                        </small>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($places_libres > 0): ?>
                <div class="availability-indicator success" aria-label="Disponibilité bonne"></div>
            <?php elseif ($places_libres === 0): ?>
                <div class="availability-indicator warning" aria-label="Parking complet"></div>
            <?php endif; ?>
        </div>
    <!-- FONCTIONNALITÉS -->
    <section class="features" aria-labelledby="features-title">
        <h2 id="features-title" class="section-title">Pourquoi choisir Parkly ?</h2>
        
        <div class="feature-grid">
            <article class="feature-card">
                <div class="feature-icon" aria-hidden="true">⚡</div>
                <h3>Réservation rapide</h3>
                <p>Réservez votre place en moins de 2 minutes, où que vous soyez.</p>
            </article>
            
            <article class="feature-card">
                <div class="feature-icon" aria-hidden="true">📱</div>
                <h3>Gestion autonome</h3>
                <p>Modifiez ou annulez vos réservations en toute autonomie depuis votre espace.</p>
            </article>
            
            <article class="feature-card">
                <div class="feature-icon" aria-hidden="true">📍</div>
                <h3>Localisation précise</h3>
                <p>Trouvez facilement les parkings les plus proches de votre destination.</p>
            </article>
            
            <article class="feature-card">
                <div class="feature-icon" aria-hidden="true">🔒</div>
                <h3>Sécurité garantie</h3>
                <p>Vos données et paiements sont protégés par un chiffrement de niveau bancaire.</p>
            </article>
        </div>
    </section>

    <!-- STATISTIQUES -->
    <?php if ($stats['total'] > 0): ?>
    <section class="stats" aria-labelledby="stats-title">
        <h2 id="stats-title" class="section-title">En temps réel</h2>
        
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number"><?= $stats['libres'] ?></span>
                <span class="stat-label">Places libres</span>
            </div>
            
            <div class="stat-item">
                <span class="stat-number"><?= $stats['occupees'] ?></span>
                <span class="stat-label">Places occupées</span>
            </div>
            
            <div class="stat-item">
                <span class="stat-number"><?= $stats['total'] ?></span>
                <span class="stat-label">Places totales</span>
            </div>
        </div>
        
        <div class="occupation-bar" role="progressbar" 
             aria-valuenow="<?= $taux_occupation ?>" 
             aria-valuemin="0" 
             aria-valuemax="100"
             aria-label="Taux d'occupation du parking">
            <div class="occupation-fill" style="width: <?= $taux_occupation ?>%"></div>
        </div>
    </section>
    <?php endif; ?>

    <!-- APPEL À L'ACTION FINAL -->
    <?php if ($places_libres === 0): ?>
    <section class="notification-section">
        <div class="notification warning">
            <h3>Parking complet</h3>
            <p>Toutes les places sont actuellement occupées. Vous pouvez vous inscrire pour être notifié dès qu'une place se libère.</p>
            <a href="/?page=notification" class="btn btn-outline">Être notifié</a>
        </div>
    </section>
    <?php endif; ?>

</main>

<!-- SCRIPTS -->
<script>
// Actualisation automatique des données de disponibilité
(function() {
    'use strict';
    
    const REFRESH_INTERVAL = 30000; // 30 secondes
    
    function updateAvailability() {
        fetch('/?page=api&action=availability')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const availabilityCard = document.querySelector('.availability-card');
                    const placesCount = document.querySelector('.places-count');
                    const occupationRate = document.querySelector('.occupation-rate');
                    
                    if (placesCount) {
                        placesCount.textContent = data.libres === 0 
                            ? 'Aucune place disponible'
                            : `${data.libres} place${data.libres > 1 ? 's' : ''} disponible${data.libres > 1 ? 's' : ''}`;
                    }
                    
                    if (occupationRate && data.total > 0) {
                        const taux = Math.round((data.occupees / data.total) * 100 * 10) / 10;
                        occupationRate.textContent = `Taux d'occupation : ${taux}%`;
                    }
                    
                    // Mise à jour de l'indicateur visuel
                    const indicator = document.querySelector('.availability-indicator');
                    if (indicator) {
                        indicator.className = data.libres > 0 
                            ? 'availability-indicator success'
                            : 'availability-indicator warning';
                    }
                }
            })
            .catch(error => {
                console.warn('Erreur lors de la mise à jour des disponibilités:', error);
            });
    }
    
    // Mise à jour périodique si la page est visible
    let intervalId;
    
    function startUpdates() {
        intervalId = setInterval(updateAvailability, REFRESH_INTERVAL);
    }
    
    function stopUpdates() {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
    }
    
    // Gestion de la visibilité de la page
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopUpdates();
        } else {
            updateAvailability();
            startUpdates();
        }
    });
    
    // Démarrage initial
    startUpdates();
})();
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>