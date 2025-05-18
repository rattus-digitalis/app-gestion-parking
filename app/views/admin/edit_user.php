<?php
$title = "Modifier utilisateur";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

if (!isset($user)) {
    echo "<main><p>Utilisateur non trouvé.</p></main>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}
?>

<main class="container edit-user">
    <header>
        <h1>Modifier l'utilisateur #<?= htmlspecialchars($user['id']) ?></h1>
    </header>

    <form action="/?page=edit_user" method="POST" class="form">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

        <div class="form-group">
            <label for="last_name">Nom</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="first_name">Prénom</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
        </div>

        <div class="form-group">
            <label for="role">Rôle</label>
            <select id="role" name="role" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Statut</label>
            <select id="status" name="status" required>
                <option value="online" <?= $user['status'] === 'online' ? 'selected' : '' ?>>En ligne</option>
                <option value="offline" <?= $user['status'] === 'offline' ? 'selected' : '' ?>>Hors ligne</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/?page=admin_users" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
