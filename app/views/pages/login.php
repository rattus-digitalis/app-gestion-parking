<?php
$title = "Connexion";
$page_css = "/assets/css/pages/login.css"; // On définit le CSS à charger
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>
<main>
    <h1>Connexion</h1>

    <form action="/?page=login" method="POST">
        <label for="email">Email :</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>
</main>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
