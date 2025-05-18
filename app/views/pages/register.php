<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte – Zenpark</title>
</head>
<body>
    <h1>Créer un compte</h1>

    <form action="/?page=register" method="POST">
        <!-- NOM -->
        <label for="last_name">Nom :</label><br>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <!-- PRÉNOM -->
        <label for="first_name">Prénom :</label><br>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <!-- EMAIL -->
        <label for="email">Adresse e-mail :</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <!-- TÉLÉPHONE -->
        <label for="phone">Numéro de téléphone :</label><br>
        <input type="tel" id="phone" name="phone" required><br><br>

        <!-- MOT DE PASSE -->
        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <!-- BOUTON -->
        <button type="submit">Créer mon compte</button>
    </form>
</body>
</html>

