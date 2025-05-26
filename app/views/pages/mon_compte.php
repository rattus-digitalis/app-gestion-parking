<?php
$title = "Mon compte";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

$user = $_SESSION['user'];
?>

<main>
    <h1>Mon compte</h1>
    <p>Gérez ici les informations de votre compte utilisateur.</p>

    <form action="/?page=mon_compte" method="POST">
        <label for="first_name">Prénom :</label>
        <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>

        <label for="last_name">Nom :</label>
        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

        <label for="phone">Téléphone :</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">

        <label for="password">Nouveau mot de passe :</label>
        <input type="password" id="password" name="password" placeholder="Laisser vide pour ne pas changer">

        <button type="submit">Mettre à jour</button>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
