<?php 
$title = "Conditions Générales d'Utilisation"; 
require_once __DIR__ . '/../templates/head.php'; 
require_once __DIR__ . '/../templates/nav.php'; 
?>

<main class="container" role="main">
    <!-- En-tête de la page -->
    <section class="text-center mb-5">
        <h1 class="section-title fade-in">Conditions Générales d'Utilisation</h1>
        <div class="alert alert-info slide-in">
            <strong>📋 Information importante :</strong> En accédant à Zenpark, vous acceptez pleinement et entièrement les conditions générales d'utilisation ci-après. Dernière mise à jour : <?= date('d/m/Y') ?>
        </div>
    </section>

    <!-- Sommaire rapide -->
    <section class="card fade-in">
        <h2>📚 Sommaire</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Objet</h3>
                <p>Définition des modalités d'utilisation</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌐</div>
                <h3>Accès</h3>
                <p>Conditions d'accès au site</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👤</div>
                <h3>Compte</h3>
                <p>Gestion de votre compte utilisateur</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Protection</h3>
                <p>Responsabilités et sécurité</p>
            </div>
        </div>
    </section>

    <!-- Contenu principal des CGU -->
    <div class="legal-content">
        <section class="card slide-in">
            <h2>🎯 1. Objet</h2>
            <p>Les présentes Conditions Générales d'Utilisation (CGU) ont pour objet de définir les modalités d'accès et d'utilisation des services du site <strong>Zenpark</strong>.</p>
            <p>Elles constituent un contrat entre vous, utilisateur du site, et Zenpark. Votre utilisation du site implique votre acceptation pleine et entière de ces conditions.</p>
        </section>

        <section class="card slide-in">
            <h2>🌐 2. Accès au site</h2>
            <div class="availability-card">
                <div class="availability-info">
                    <div class="availability-icon">🕐</div>
                    <div class="availability-text">
                        <span class="places-count">24h/24</span>
                        <span class="occupation-rate">Accès libre et gratuit</span>
                    </div>
                </div>
                <div class="availability-indicator success"></div>
            </div>
            <p>Le site est accessible gratuitement à tout utilisateur disposant d'un accès à Internet. Tous les frais liés à l'accès au site (frais matériels, logiciels ou d'accès à Internet) sont exclusivement à la charge de l'utilisateur.</p>
            <div class="alert alert-warning">
                <strong>⚠️ Important :</strong> Zenpark se réserve le droit de suspendre temporairement l'accès au site pour des raisons de maintenance ou de mise à jour.
            </div>
        </section>

        <section class="card slide-in">
            <h2>👤 3. Compte utilisateur</h2>
            <p>Pour bénéficier de nos services de réservation, vous devez créer un compte personnel. Vous vous engagez à :</p>
            <div class="reservation-card">
                <ul>
                    <li><strong>Exactitude :</strong> <span>Fournir des informations exactes et véridiques</span></li>
                    <li><strong>Mise à jour :</strong> <span>Maintenir vos informations à jour</span></li>
                    <li><strong>Confidentialité :</strong> <span>Protéger vos identifiants de connexion</span></li>
                    <li><strong>Usage personnel :</strong> <span>Ne pas partager votre compte avec des tiers</span></li>
                </ul>
            </div>
        </section>

        <section class="card slide-in">
            <h2>🛡️ 4. Responsabilités</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Sécurisé</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Surveillance</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">0</span>
                    <span class="stat-label">Frais cachés</span>
                </div>
            </div>
            <p><strong>Responsabilité de Zenpark :</strong> Nous nous engageons à fournir un service de qualité et à protéger vos données. Cependant, Zenpark ne peut être tenu responsable des dommages directs ou indirects causés au matériel de l'utilisateur lors de l'accès au site.</p>
            <p><strong>Responsabilité de l'utilisateur :</strong> Vous vous engagez à utiliser le site de manière conforme à sa destination et à ne pas porter atteinte aux droits de tiers.</p>
        </section>

        <section class="card slide-in">
            <h2>📝 5. Propriété intellectuelle</h2>
            <div class="alert alert-info">
                <strong>🔒 Protection des contenus :</strong> Tous les contenus du site Zenpark (textes, images, logos, design) sont protégés par le droit d'auteur et la propriété intellectuelle.
            </div>
            <p>Toute reproduction ou représentation totale ou partielle du site sans autorisation expresse est strictement interdite et constituerait une contrefaçon sanctionnée par les articles L.335-2 et suivants du Code de la propriété intellectuelle.</p>
        </section>

        <section class="card slide-in">
            <h2>🔐 6. Données personnelles</h2>
            <p>Zenpark collecte et traite vos données personnelles dans le strict respect du Règlement Général sur la Protection des Données (RGPD).</p>
            <div class="reservation-card">
                <h3>Vos droits :</h3>
                <ul>
                    <li><strong>Droit d'accès :</strong> <span>Consulter vos données</span></li>
                    <li><strong>Droit de rectification :</strong> <span>Corriger vos informations</span></li>
                    <li><strong>Droit à l'effacement :</strong> <span>Supprimer vos données</span></li>
                    <li><strong>Droit à la portabilité :</strong> <span>Récupérer vos données</span></li>
                </ul>
            </div>
            <div class="reservation-actions">
                <a href="/?page=contact" class="btn btn-primary">
                    📧 Exercer mes droits
                </a>
                <a href="/?page=privacy" class="btn btn-outline">
                    📋 Politique de confidentialité
                </a>
            </div>
        </section>

        <section class="card slide-in">
            <h2>🔄 7. Modification des CGU</h2>
            <div class="alert alert-warning">
                <strong>📢 Mise à jour :</strong> Zenpark se réserve le droit de modifier à tout moment les présentes CGU pour s'adapter à l'évolution de nos services ou de la réglementation.
            </div>
            <p>Il appartient à l'utilisateur de consulter régulièrement cette page pour prendre connaissance des éventuelles modifications. Votre utilisation continue du site après modification des CGU vaut acceptation des nouvelles conditions.</p>
        </section>

        <section class="card slide-in">
            <h2>⚖️ 8. Droit applicable et juridiction</h2>
            <p>Les présentes CGU sont régies par le droit français. En cas de litige relatif à l'interprétation ou à l'exécution des présentes, et à défaut d'accord amiable, les tribunaux français seront seuls compétents.</p>
            <div class="alert alert-info">
                <strong>🤝 Médiation :</strong> Conformément à la réglementation, nous privilégions toujours la résolution amiable des différends avant tout recours judiciaire.
            </div>
        </section>

        <!-- Section contact et support -->
        <section class="card slide-in">
            <h2>📞 Contact et support</h2>
            <p>Pour toute question relative aux présentes CGU ou à l'utilisation de nos services, n'hésitez pas à nous contacter :</p>
            <div class="reservation-actions">
                <a href="/?page=contact" class="btn btn-primary">
                    💬 Nous contacter
                </a>
                <a href="/?page=faq" class="btn btn-secondary">
                    ❓ FAQ
                </a>
                <a href="/?page=support" class="btn btn-info">
                    🛠️ Support technique
                </a>
            </div>
        </section>
    </div>

    <!-- Footer de la page avec informations importantes -->
    <section class="text-center mt-5">
        <div class="alert alert-success">
            <strong>✅ Document à jour :</strong> Ces conditions générales d'utilisation ont été mises à jour le <?= date('d/m/Y à H:i') ?> et sont en vigueur immédiatement.
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>