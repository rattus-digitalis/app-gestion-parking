<?php
$title = "Contact";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

// Traitement du formulaire
$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validation
    if (empty($name)) {
        $errors['name'] = 'Le nom est requis';
    } elseif (strlen($name) < 2) {
        $errors['name'] = 'Le nom doit contenir au moins 2 caractères';
    }
    
    if (empty($email)) {
        $errors['email'] = 'L\'adresse email est requise';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L\'adresse email n\'est pas valide';
    }
    
    if (empty($subject)) {
        $errors['subject'] = 'Le sujet est requis';
    }
    
    if (empty($message)) {
        $errors['message'] = 'Le message est requis';
    } elseif (strlen($message) < 10) {
        $errors['message'] = 'Le message doit contenir au moins 10 caractères';
    }
    
    // Si pas d'erreurs, traiter l'envoi
    if (empty($errors)) {
        // Ici vous pouvez ajouter l'envoi d'email ou l'enregistrement en base
        // mail($to, $subject, $message, $headers);
        // ou insert en base de données
        
        $success = true;
        // Réinitialiser les variables pour vider le formulaire
        $name = $email = $subject = $message = '';
    }
}
?>

<main class="container" role="main">
    <!-- En-tête de la page -->
    <section class="text-center mb-5">
        <h1 class="section-title fade-in">Contactez-nous</h1>
        <p class="text-secondary mb-4 slide-in">
            Vous avez une question, un problème ou une suggestion ? Nous sommes là pour vous aider. 
            Envoyez-nous un message et nous vous répondrons dans les plus brefs délais.
        </p>
        
        <!-- Numéro d'urgence bien visible -->
        <div class="availability-card fade-in">
            <div class="availability-info">
                <div class="availability-icon">📞</div>
                <div class="availability-text">
                    <span class="places-count">Urgence</span>
                    <span class="occupation-rate">7j/7, 24h/24</span>
                </div>
            </div>
            <div class="reservation-actions">
                <a href="tel:+33187654321" class="btn btn-danger">
                    🚨 +33 1 87 65 43 21
                </a>
            </div>
        </div>
    </section>

    <!-- Section rapide d'aide -->
    <section class="mb-5">
        <h2 class="text-center mb-4">🚀 Besoin d'aide rapidement ?</h2>
        <div class="feature-grid fade-in">
            <div class="feature-card">
                <div class="feature-icon">❓</div>
                <h3>FAQ</h3>
                <p>Consultez nos questions fréquentes</p>
                <a href="/?page=faq" class="btn btn-outline btn-sm">Voir la FAQ</a>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛠️</div>
                <h3>Support</h3>
                <p>Aide technique instantanée</p>
                <a href="/?page=support" class="btn btn-outline btn-sm">Support</a>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📋</div>
                <h3>Guides</h3>
                <p>Tutoriels et documentation</p>
                <a href="/?page=guides" class="btn btn-outline btn-sm">Guides</a>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💬</div>
                <h3>Chat Live</h3>
                <p>Discussion en temps réel</p>
                <button class="btn btn-success btn-sm" onclick="openLiveChat()">Chatter</button>
            </div>
        </div>
    </section>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">
        <!-- Section principale : Formulaire -->
        <section class="slide-in" aria-labelledby="form-title">
            <div class="card">
                <h2 id="form-title">📝 Formulaire de contact</h2>

                <!-- Messages de statut -->
                <?php if ($success): ?>
                    <div class="alert alert-success fade-in" role="alert" aria-live="polite">
                        <strong>✅ Message envoyé avec succès !</strong> 
                        Nous vous répondrons dans les plus brefs délais.
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger fade-in" role="alert" aria-live="assertive">
                        <strong>❌ Erreurs détectées :</strong>
                        <ul style="margin: 0.5rem 0 0 1.5rem;">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Formulaire principal -->
                <form action="/?page=contact" method="POST" novalidate>
                    <!-- Informations personnelles -->
                    <fieldset style="border: none; padding: 0; margin: 0;">
                        <legend class="visually-hidden">Informations personnelles</legend>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <!-- Nom -->
                            <div class="form-group">
                                <label class="form-label" for="name">
                                    👤 Nom complet 
                                    <span style="color: var(--danger-color);" aria-label="champ obligatoire">*</span>
                                </label>
                                <input 
                                    class="form-input" 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="<?= htmlspecialchars($name ?? '') ?>"
                                    required 
                                    aria-required="true"
                                    maxlength="100"
                                    autocomplete="name"
                                    placeholder="Votre nom complet"
                                >
                                <?php if (isset($errors['name'])): ?>
                                    <span class="form-error" role="alert">
                                        <?= htmlspecialchars($errors['name']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label class="form-label" for="email">
                                    📧 Adresse email 
                                    <span style="color: var(--danger-color);" aria-label="champ obligatoire">*</span>
                                </label>
                                <input 
                                    class="form-input" 
                                    type="email" 
                                    id="email" 
                                    name="email"
                                    value="<?= htmlspecialchars($email ?? '') ?>"
                                    required 
                                    aria-required="true"
                                    maxlength="255"
                                    autocomplete="email"
                                    placeholder="votre.email@exemple.com"
                                >
                                <?php if (isset($errors['email'])): ?>
                                    <span class="form-error" role="alert">
                                        <?= htmlspecialchars($errors['email']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Sujet -->
                    <div class="form-group">
                        <label class="form-label" for="subject">
                            🏷️ Sujet 
                            <span style="color: var(--danger-color);" aria-label="champ obligatoire">*</span>
                        </label>
                        <select 
                            class="form-select" 
                            id="subject" 
                            name="subject" 
                            required 
                            aria-required="true"
                        >
                            <option value="">Sélectionnez un sujet</option>
                            <option value="question" <?= ($subject ?? '') === 'question' ? 'selected' : '' ?>>
                                ❓ Question générale
                            </option>
                            <option value="support" <?= ($subject ?? '') === 'support' ? 'selected' : '' ?>>
                                🛠️ Support technique
                            </option>
                            <option value="suggestion" <?= ($subject ?? '') === 'suggestion' ? 'selected' : '' ?>>
                                💡 Suggestion d'amélioration
                            </option>
                            <option value="bug" <?= ($subject ?? '') === 'bug' ? 'selected' : '' ?>>
                                🐛 Signaler un problème
                            </option>
                            <option value="partnership" <?= ($subject ?? '') === 'partnership' ? 'selected' : '' ?>>
                                🤝 Partenariat
                            </option>
                            <option value="other" <?= ($subject ?? '') === 'other' ? 'selected' : '' ?>>
                                📋 Autre
                            </option>
                        </select>
                        <?php if (isset($errors['subject'])): ?>
                            <span class="form-error" role="alert">
                                <?= htmlspecialchars($errors['subject']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Message -->
                    <div class="form-group">
                        <label class="form-label" for="message">
                            💬 Message 
                            <span style="color: var(--danger-color);" aria-label="champ obligatoire">*</span>
                        </label>
                        <textarea 
                            class="form-textarea" 
                            id="message" 
                            name="message" 
                            rows="6" 
                            required 
                            aria-required="true"
                            maxlength="2000"
                            placeholder="Décrivez votre demande en détail..."
                        ><?= htmlspecialchars($message ?? '') ?></textarea>
                        <small style="color: var(--text-muted); font-size: var(--font-size-sm);">
                            📏 Minimum 10 caractères, maximum 2000 caractères
                        </small>
                        <?php if (isset($errors['message'])): ?>
                            <span class="form-error" role="alert">
                                <?= htmlspecialchars($errors['message']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Options -->
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input 
                                type="checkbox" 
                                name="newsletter" 
                                value="1" 
                                <?= isset($_POST['newsletter']) ? 'checked' : '' ?>
                                style="margin: 0;"
                            >
                            <span>📬 Je souhaite recevoir la newsletter (optionnel)</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="reservation-actions">
                        <button type="submit" class="btn btn-primary">
                            🚀 Envoyer le message
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            🔄 Réinitialiser
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Section secondaire : Informations de contact -->
        <aside class="slide-in" aria-labelledby="contact-info-title">
            <div class="card">
                <h2 id="contact-info-title">📞 Autres moyens de contact</h2>
                
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="reservation-card">
                        <h3 style="margin: 0 0 1rem 0; color: var(--text-accent);">📱 Par téléphone</h3>
                        <ul>
                            <li>
                                <strong>Numéro :</strong>
                                <span><a href="tel:+33123456789" style="color: var(--primary-color); text-decoration: none;">+33 1 23 45 67 89</a></span>
                            </li>
                            <li>
                                <strong>Horaires :</strong>
                                <span>Lun-Ven, 9h-18h</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="reservation-card">
                        <h3 style="margin: 0 0 1rem 0; color: var(--text-accent);">📧 Par email direct</h3>
                        <ul>
                            <li>
                                <strong>Email :</strong>
                                <span><a href="mailto:contact@zenpark.com" style="color: var(--primary-color); text-decoration: none;">contact@zenpark.com</a></span>
                            </li>
                            <li>
                                <strong>Délai :</strong>
                                <span>Réponse sous 24h</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="reservation-card">
                        <h3 style="margin: 0 0 1rem 0; color: var(--text-accent);">📍 Adresse postale</h3>
                        <address style="font-style: normal; line-height: 1.6;">
                            <strong>ZenPark</strong><br>
                            123 Rue de l'Innovation<br>
                            75001 Paris<br>
                            France
                        </address>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <!-- Section FAQ rapide -->
    <section class="mt-5">
        <div class="card fade-in">
            <h2 class="text-center">🤔 Questions fréquentes</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">⏰</div>
                    <h3>Horaires d'ouverture</h3>
                    <p>Nos parkings sont accessibles 24h/24, 7j/7. Le support client est disponible de 9h à 18h en semaine.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💳</div>
                    <h3>Moyens de paiement</h3>
                    <p>Nous acceptons toutes les cartes bancaires, PayPal et les paiements mobiles (Apple Pay, Google Pay).</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Sécurité</h3>
                    <p>Tous nos parkings sont sécurisés avec surveillance vidéo, éclairage et accès contrôlé.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function openLiveChat() {
    alert('💬 Le chat live sera bientôt disponible ! En attendant, utilisez le formulaire de contact.');
}

// Animation des compteurs
document.addEventListener('DOMContentLoaded', function() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'pulse 2s ease-in-out infinite';
            }
        });
    });
    
    statNumbers.forEach(stat => {
        observer.observe(stat);
    });
});
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>