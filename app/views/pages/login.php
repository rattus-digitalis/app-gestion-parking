<?php
$title = "Connexion";
$page_css = "/assets/css/pages/login.css"; // CSS spécifique à cette page
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="login-page container" role="main">
    <section class="login-box">
        <h1>🔐 Connexion</h1>

        <form action="/?page=login" method="POST" aria-label="Formulaire de connexion">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Se connecter</button>
                <a href="/?page=register" class="btn btn-secondary">Créer un compte</a>
            </div>
        </form>
    </section>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
