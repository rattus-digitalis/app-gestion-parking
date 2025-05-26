<?php
$title = "Ma voiture";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Mon véhicule</h1>

    <form method="POST" action="/?page=ma_voiture">
        <label for="marque">Marque</label>
        <input type="text" id="marque" name="marque" value="<?= htmlspecialchars($car['marque'] ?? '') ?>" required>

        <label for="modele">Modèle</label>
        <input type="text" id="modele" name="modele" value="<?= htmlspecialchars($car['modele'] ?? '') ?>" required>

        <label for="immatriculation">Immatriculation</label>
        <input type="text" id="immatriculation" name="immatriculation" value="<?= htmlspecialchars($car['immatriculation'] ?? '') ?>" required>

        <label for="couleur">Couleur</label>
        <input type="text" id="couleur" name="couleur" value="<?= htmlspecialchars($car['couleur'] ?? '') ?>" required>

        <label for="type">Type de véhicule</label>
        <select id="type" name="type" required>
            <option value="voiture" <?= (isset($car['type']) && $car['type'] === 'voiture') ? 'selected' : '' ?>>Voiture</option>
            <option value="moto" <?= (isset($car['type']) && $car['type'] === 'moto') ? 'selected' : '' ?>>Moto</option>
        </select>

        <button type="submit">Enregistrer</button>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
