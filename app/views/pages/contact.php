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
    <header class="page-header">
        <h1>Contactez-nous</h1>
        <p class="page-description">
            Vous avez une question, un problème ou une suggestion ? Nous sommes là pour vous aider. 
            Envoyez-nous un message via ce formulaire et nous vous répondrons dans les plus brefs délais.
        </p>
        
        <!-- Numéro d'urgence bien visible -->
        <div class="emergency-contact">
            <strong>En cas d'urgence :</strong> 
            <a href="tel:+33187654321" class="emergency-phone">+33 1 87 65 43 21</a>
            <small>(7j/7, 24h/24)</small>
        </div>
    </header>

    <div class="contact-container">
        <!-- Section principale : Formulaire -->
        <section class="contact-form-section" aria-labelledby="form-title">
            <h2 id="form-title" class="visually-hidden">Formulaire de contact</h2>

            <!-- Messages de statut -->
            <?php if ($success): ?>
                <div class="alert alert-success" role="alert" aria-live="polite">
                    <strong>Message envoyé avec succès !</strong> 
                    Nous vous répondrons dans les plus brefs délais.
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert" aria-live="assertive">
                    <strong>Erreurs détectées :</strong>
                    <ul class="error-list">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Formulaire principal -->
            <form class="contact-form" action="/?page=contact" method="POST" novalidate>
                <fieldset class="form-fieldset">
                    <legend class="visually-hidden">Informations personnelles</legend>
                    
                    <!-- Nom -->
                    <div class="form-group">
                        <label class="form-label" for="name">
                            Nom complet 
                            <span class="required" aria-label="champ obligatoire">*</span>
                        </label>
                        <input 
                            class="form-input <?= isset($errors['name']) ? 'form-input--error' : '' ?>" 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?= htmlspecialchars($name ?? '') ?>"
                            required 
                            aria-required="true"
                            aria-describedby="<?= isset($errors['name']) ? 'name-error' : '' ?>"
                            maxlength="100"
                            autocomplete="name"
                        >
                        <?php if (isset($errors['name'])): ?>
                            <span id="name-error" class="form-error" role="alert">
                                <?= htmlspecialchars($errors['name']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="email">
                            Adresse email 
                            <span class="required" aria-label="champ obligatoire">*</span>
                        </label>
                        <input 
                            class="form-input <?= isset($errors['email']) ? 'form-input--error' : '' ?>" 
                            type="email" 
                            id="email" 
                            name="email"
                            value="<?= htmlspecialchars($email ?? '') ?>"
                            required 
                            aria-required="true"
                            aria-describedby="<?= isset($errors['email']) ? 'email-error' : '' ?>"
                            maxlength="255"
                            autocomplete="email"
                        >
                        <?php if (isset($errors['email'])): ?>
                            <span id="email-error" class="form-error" role="alert">
                                <?= htmlspecialchars($errors['email']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </fieldset>

                <fieldset class="form-fieldset">
                    <legend class="visually-hidden">Votre demande</legend>
                    
                    <!-- Sujet -->
                    <div class="form-group">
                        <label class="form-label" for="subject">
                            Sujet 
                            <span class="required" aria-label="champ obligatoire">*</span>
                        </label>
                        <select 
                            class="form-select <?= isset($errors['subject']) ? 'form-select--error' : '' ?>" 
                            id="subject" 
                            name="subject" 
                            required 
                            aria-required="true"
                            aria-describedby="<?= isset($errors['subject']) ? 'subject-error' : '' ?>"
                        >
                            <option value="">Sélectionnez un sujet</option>
                            <option value="question" <?= ($subject ?? '') === 'question' ? 'selected' : '' ?>>
                                Question générale
                            </option>
                            <option value="support" <?= ($subject ?? '') === 'support' ? 'selected' : '' ?>>
                                Support technique
                            </option>
                            <option value="suggestion" <?= ($subject ?? '') === 'suggestion' ? 'selected' : '' ?>>
                                Suggestion d'amélioration
                            </option>
                            <option value="bug" <?= ($subject ?? '') === 'bug' ? 'selected' : '' ?>>
                                Signaler un problème
                            </option>
                            <option value="partnership" <?= ($subject ?? '') === 'partnership' ? 'selected' : '' ?>>
                                Partenariat
                            </option>
                            <option value="other" <?= ($subject ?? '') === 'other' ? 'selected' : '' ?>>
                                Autre
                            </option>
                        </select>
                        <?php if (isset($errors['subject'])): ?>
                            <span id="subject-error" class="form-error" role="alert">
                                <?= htmlspecialchars($errors['subject']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Message -->
                    <div class="form-group">
                        <label class="form-label" for="message">
                            Message 
                            <span class="required" aria-label="champ obligatoire">*</span>
                        </label>
                        <textarea 
                            class="form-textarea <?= isset($errors['message']) ? 'form-textarea--error' : '' ?>" 
                            id="message" 
                            name="message" 
                            rows="6" 
                            required 
                            aria-required="true"
                            aria-describedby="message-help <?= isset($errors['message']) ? 'message-error' : '' ?>"
                            maxlength="2000"
                            placeholder="Décrivez votre demande en détail..."
                        ><?= htmlspecialchars($message ?? '') ?></textarea>
                        <small id="message-help" class="form-help">
                            Minimum 10 caractères, maximum 2000 caractères
                        </small>
                        <?php if (isset($errors['message'])): ?>
                            <span id="message-error" class="form-error" role="alert">
                                <?= htmlspecialchars($errors['message']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </fieldset>

                <!-- Options -->
                <fieldset class="form-fieldset">
                    <legend class="visually-hidden">Options</legend>
                    
                    <div class="form-group form-group--checkbox">
                        <label class="form-checkbox">
                            <input 
                                class="form-checkbox-input" 
                                type="checkbox" 
                                name="newsletter" 
                                value="1" 
                                <?= isset($_POST['newsletter']) ? 'checked' : '' ?>
                            >
                            <span class="form-checkbox-label">
                                Je souhaite recevoir la newsletter (optionnel)
                            </span>
                        </label>
                    </div>
                </fieldset>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        Envoyer le message
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        Réinitialiser
                    </button>
                </div>
            </form>
        </section>

        <!-- Section secondaire : Informations de contact -->
        <aside class="contact-info-section" aria-labelledby="contact-info-title">
            <h2 id="contact-info-title">Autres moyens de nous contacter</h2>
            
            <div class="contact-methods">
                <article class="contact-method">
                    <h3 class="contact-method-title">Par téléphone</h3>
                    <div class="contact-method-content">
                        <p class="contact-method-value">
                            <a href="tel:+33123456789" class="contact-link">+33 1 23 45 67 89</a>
                        </p>
                        <p class="contact-method-hours">
                            <small>Du lundi au vendredi, 9h-18h</small>
                        </p>
                    </div>
                </article>
                
                <article class="contact-method">
                    <h3 class="contact-method-title">Par email direct</h3>
                    <div class="contact-method-content">
                        <p class="contact-method-value">
                            <a href="mailto:contact@monsite.com" class="contact-link">contact@parkly.com</a>
                        </p>
                    </div>
                </article>
                
                <article class="contact-method">
                    <h3 class="contact-method-title">Adresse postale</h3>
                    <div class="contact-method-content">
                        <address class="contact-address">
                            123 Rue de l'Exemple<br>
                            75001 Paris<br>
                            France
                        </address>
                    </div>
                </article>
            </div>
        </aside>
    </div>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>