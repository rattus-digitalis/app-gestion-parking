<?php
$title = "Modifier utilisateur";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

if (!isset($user)) {
    echo "<p>Utilisateur non trouvé.</p>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}
?>

<main>
    <h1>Modifier utilisateur #<?= htmlspecialchars($user['id']) ?></h1>

    <form action="/?page=edit_user" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

        <label for="last_name">Nom :</label><br>
        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required><br><br>

        <label for="first_name">Prénom :</label><br>
        <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required><br><br>

        <label for="email">Email :</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

        <label for="phone">Téléphone :</label><br>
        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required><br><br>

        <label for="role">Rôle :</label><br>
        <select id="role" name="role" required>
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        </select><br><br>

        <label for="status">Statut :</label><br>
        <select id="status" name="status" required>
            <option value="online" <?= $user['status'] === 'online' ? 'selected' : '' ?>>En ligne</option>
            <option value="offline" <?= $user['status'] === 'offline' ? 'selected' : '' ?>>Hors ligne</option>
        </select><br><br>

        <button type="submit">Enregistrer</button>
        <a href="/?page=admin_users" style="margin-left:1em;">Annuler</a>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
