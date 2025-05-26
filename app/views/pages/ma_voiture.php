<?php
$title = "Ma voiture";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Ma voiture</h1>

    <form method="POST" action="/?page=ma_voiture">
        <label>Marque</label>
        <input type="text" name="marque" value="<?= htmlspecialchars($car['marque'] ?? '') ?>">

        <label>Modèle</label>
        <input type="text" name="modele" value="<?= htmlspecialchars($car['modele'] ?? '') ?>">

        <label>Immatriculation</label>
        <input type="text" name="immatriculation" value="<?= htmlspecialchars($car['immatriculation'] ?? '') ?>">

        <label>Couleur</label>
        <input type="text" name="couleur" value="<?= htmlspecialchars($car['couleur'] ?? '') ?>">

        <button type="submit">Enregistrer</button>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
