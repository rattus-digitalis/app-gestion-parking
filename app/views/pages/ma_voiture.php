<?php
$title = "Ma voiture";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container car-form" role="main">
    <h1>🚗 Mon véhicule</h1>

    <form method="POST" action="/?page=ma_voiture">
        <div class="form-group">
            <label for="marque">Marque</label>
            <input type="text" id="marque" name="marque" value="<?= htmlspecialchars($car['marque'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="modele">Modèle</label>
            <input type="text" id="modele" name="modele" value="<?= htmlspecialchars($car['modele'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="immatriculation">Immatriculation</label>
            <input type="text" id="immatriculation" name="immatriculation" value="<?= htmlspecialchars($car['immatriculation'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="couleur">Couleur</label>
            <input type="text" id="couleur" name="couleur" value="<?= htmlspecialchars($car['couleur'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="type">Type de véhicule</label>
            <select id="type" name="type" required>
                <option value="voiture" <?= (isset($car['type']) && $car['type'] === 'voiture') ? 'selected' : '' ?>>Voiture</option>
                <option value="moto" <?= (isset($car['type']) && $car['type'] === 'moto') ? 'selected' : '' ?>>Moto</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
            <a href="/?page=dashboard_user" class="btn btn-secondary">← Retour</a>
        </div>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
