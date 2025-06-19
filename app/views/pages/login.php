<?php
$title = "Connexion";
$page_css = "/assets/css/pages/login.css"; // CSS spécifique à cette page
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="login-page container" role="main">
    <div class="login-wrapper">
        <section class="login-box" aria-labelledby="login-title">
            <header class="login-header">
                <h1 id="login-title">Connexion</h1>
                <p class="login-subtitle">Accédez à votre compte</p>
            </header>

            <div class="login-content">
                <form action="/?page=login" method="POST" class="login-form" 
                      aria-label="Formulaire de connexion" novalidate>
                    
                    <fieldset class="form-fields">
                        <legend class="sr-only">Informations de connexion</legend>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">
                                Adresse email
                                <span class="required" aria-label="requis">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input" 
                                   required 
                                   autocomplete="email"
                                   aria-describedby="email-error"
                                   placeholder="votre@email.com">
                            <div id="email-error" class="error-message" role="alert" aria-live="polite"></div>
                        </div>

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
                                       autocomplete="current-password"
                                       aria-describedby="password-error"
                                       minlength="8">
                            
                            </div>
                            <div id="password-error" class="error-message" role="alert" aria-live="polite"></div>
                        </div>
                    </fieldset>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember" 
                                   class="form-checkbox"
                                   value="1">
                            <label for="remember" class="checkbox-label">Se souvenir de moi</label>
                        </div>
                        
                        <a href="/?page=forgot-password" class="forgot-password-link">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-login">
                            <span class="btn-text">Se connecter</span>
                            <span class="btn-loading" aria-hidden="true">Connexion...</span>
                        </button>
                    </div>
                </form>
            </div>

            <footer class="login-footer">
                <p class="register-prompt">
                    Pas encore de compte ? 
                    <a href="/?page=register" class="register-link">Créer un compte</a>
                </p>
            </footer>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>