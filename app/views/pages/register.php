<?php
$title = "Créer un compte";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container register-form" role="main">
    <h1>Créer un compte</h1>

    <form action="/?page=register" method="POST" aria-label="Formulaire de création de compte">
        <div class="form-group">
            <label for="last_name">Nom</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>

        <div class="form-group">
            <label for="first_name">Prénom</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>

        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" required autocomplete="email">
        </div>

        <div class="form-group">
            <label for="phone">Numéro de téléphone</label>
            <input type="tel" id="phone" name="phone" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required autocomplete="new-password">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Créer le compte</button>
        </div>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
