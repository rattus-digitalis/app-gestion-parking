<?php
$title = "Créer un compte";
$page_css = "/assets/css/pages/register.css"; // CSS spécifique à cette page
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="register-page container" role="main">
    <div class="register-wrapper">
        <section class="register-box" aria-labelledby="register-title">
            <header class="register-header">
                <h1 id="register-title">Créer un compte</h1>
                <p class="register-subtitle">Rejoignez-nous en quelques minutes</p>
            </header>

            <div class="register-content">
                <form action="/?page=register" method="POST" class="register-form" 
                      aria-label="Formulaire de création de compte" novalidate>
                    
                    <fieldset class="form-section personal-info">
                        <legend class="section-title">Informations personnelles</legend>
                        
                        <div class="form-row">
                            <div class="form-group form-group-half">
                                <label for="last_name" class="form-label">
                                    Nom
                                    <span class="required" aria-label="requis">*</span>
                                </label>
                                <input type="text" 
                                       id="last_name" 
                                       name="last_name" 
                                       class="form-input" 
                                       required
                                       autocomplete="family-name"
                                       aria-describedby="last_name-error"
                                       placeholder="Votre nom de famille"
                                       minlength="2"
                                       maxlength="50">
                                <div id="last_name-error" class="error-message" role="alert" aria-live="polite"></div>
                            </div>

                            <div class="form-group form-group-half">
                                <label for="first_name" class="form-label">
                                    Prénom
                                    <span class="required" aria-label="requis">*</span>
                                </label>
                                <input type="text" 
                                       id="first_name" 
                                       name="first_name" 
                                       class="form-input" 
                                       required
                                       autocomplete="given-name"
                                       aria-describedby="first_name-error"
                                       placeholder="Votre prénom"
                                       minlength="2"
                                       maxlength="50">
                                <div id="first_name-error" class="error-message" role="alert" aria-live="polite"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                Adresse e-mail
                                <span class="required" aria-label="requis">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input" 
                                   required 
                                   autocomplete="email"
                                   aria-describedby="email-error email-help"
                                   placeholder="votre@email.com">
                            <div id="email-help" class="form-help">
                                Cette adresse sera utilisée pour vous connecter
                            </div>
                            <div id="email-error" class="error-message" role="alert" aria-live="polite"></div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">
                                Numéro de téléphone
                                <span class="required" aria-label="requis">*</span>
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   class="form-input" 
                                   required
                                   autocomplete="tel"
                                   aria-describedby="phone-error phone-help"
                                   placeholder="06 12 34 56 78"
                                   pattern="^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$">
                            <div id="phone-help" class="form-help">
                                Format : 06 12 34 56 78 ou +33 6 12 34 56 78
                            </div>
                            <div id="phone-error" class="error-message" role="alert" aria-live="polite"></div>
                        </div>
                    </fieldset>

                    <fieldset class="form-section security-info">
                        <legend class="section-title">Sécurité</legend>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">
                                Mot de passe
                                <span class="required" aria-label="requis">*</span>
                            </label>
                            <div class="password-wrapper">
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-input" 
                                       required 
                                       autocomplete="new-password"
                                       aria-describedby="password-error password-help"
                                       minlength="8"
                                       maxlength="128">
                            </div>
                            <div id="password-help" class="form-help">
                                <div class="password-requirements">
                                    <p>Le mot de passe doit contenir :</p>
                                    <ul class="requirements-list">
                                        <li class="requirement" data-requirement="length">Au moins 8 caractères</li>
                                        <li class="requirement" data-requirement="lowercase">Une lettre minuscule</li>
                                        <li class="requirement" data-requirement="uppercase">Une lettre majuscule</li>
                                        <li class="requirement" data-requirement="number">Un chiffre</li>
                                        <li class="requirement" data-requirement="special">Un caractère spécial</li>
                                    </ul>
                                </div>
                            </div>
                            <div id="password-error" class="error-message" role="alert" aria-live="polite"></div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirm" class="form-label">
                                Confirmer le mot de passe
                                <span class="required" aria-label="requis">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirm" 
                                   name="password_confirm" 
                                   class="form-input" 
                                   required
                                   autocomplete="new-password"
                                   aria-describedby="password_confirm-error">
                            <div id="password_confirm-error" class="error-message" role="alert" aria-live="polite"></div>
                        </div>
                    </fieldset>

                    <fieldset class="form-section agreements">
                        <legend class="section-title">Conditions d'utilisation</legend>
                        
                        <div class="form-group checkbox-group">
                            <input type="checkbox" 
                                   id="terms" 
                                   name="terms" 
                                   class="form-checkbox" 
                                   required
                                   aria-describedby="terms-error">
                            <label for="terms" class="checkbox-label">
                                J'accepte les 
                                <a href="/?page=cgu" target="_blank" rel="noopener">
                                    conditions générales d'utilisation
                                </a>  
                            </label>
                            <div id="terms-error" class="error-message" role="alert" aria-live="polite"></div>
                        </div>
                    </fieldset>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-register">
                            <span class="btn-text">Créer mon compte</span>
                        
                        </button>
                    </div>
                </form>
            </div>

            <footer class="register-footer">
                <p class="login-prompt">
                    Déjà un compte ? 
                    <a href="/?page=login" class="login-link">Se connecter</a>
                </p>
            </footer>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>