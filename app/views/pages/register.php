<?php
$title = "Créer un compte";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Créer un compte</h1>

    <form action="/?page=register" method="POST">
        <label for="last_name">Nom :</label><br>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="first_name">Prénom :</label><br>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="email">Adresse e-mail :</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="phone">Numéro de téléphone :</label><br>
        <input type="tel" id="phone" name="phone" required><br><br>

        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Créer le compte</button>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
