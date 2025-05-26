<?php
$title = "Nouvelle Réservation";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Réserver une place</h1>

    <form method="post">
        <label for="car_id">Votre voiture :</label>
        <select name="car_id" id="car_id" required>
            <?php if (is_array($cars)): ?>
                <?php foreach ($cars as $car): ?>
                    <option value="<?= $car['id'] ?>">
                        <?= htmlspecialchars($car['marque'] . ' ' . $car['modele'] . ' (' . $car['immatriculation'] . ')') ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="<?= $cars['id'] ?>">
                    <?= htmlspecialchars($cars['marque'] . ' ' . $cars['modele'] . ' (' . $cars['immatriculation'] . ')') ?>
                </option>
            <?php endif; ?>
        </select>

        <label for="parking_id">Place de parking :</label>
        <select name="parking_id" id="parking_id" required>
            <?php foreach ($parkings as $parking): ?>
                <option value="<?= $parking['id'] ?>">
                    <?= "Étage {$parking['etage']} – Place {$parking['numero_place']}" ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="date_start">Date de début :</label>
        <input type="datetime-local" name="date_start" required>

        <label for="date_end">Date de fin :</label>
        <input type="datetime-local" name="date_end" required>

        <button type="submit">Réserver</button>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
