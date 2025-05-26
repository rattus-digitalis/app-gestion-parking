<?php
$title = "Mon compte";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

$user = $_SESSION['user'];
?>

<main class="container account-form" role="main">
    <h1>👤 Mon compte</h1>
    <p>Gérez ici les informations de votre compte utilisateur.</p>

    <form action="/?page=mon_compte" method="POST">
        <div class="form-group">
            <label for="first_name">Prénom</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="last_name">Nom</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Nouveau mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Laissez vide pour ne pas modifier">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Mettre à jour</button>
            <a href="/?page=dashboard_user" class="btn btn-secondary">← Retour</a>
        </div>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
