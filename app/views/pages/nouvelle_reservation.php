<?php
$title = "Nouvelle réservation";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Nouvelle réservation</h1>

    <form method="POST" action="/?page=nouvelle_reservation">
        <!-- Voiture -->
        <label for="car">Votre voiture :</label>
        <input type="text" id="car_display" name="car_display"
            value="<?= isset($car) 
                ? htmlspecialchars(($car['marque'] ?? '') . ' ' . ($car['modele'] ?? '') . ' (' . ($car['immatriculation'] ?? '') . ')') 
                : 'Aucune voiture enregistrée'
            ?>" 
            readonly>

        <?php if (isset($car['id'])): ?>
            <input type="hidden" name="car_id" value="<?= htmlspecialchars($car['id']) ?>">
        <?php endif; ?>

        <!-- Choix de la place -->
        <label for="parking_id">Choisissez votre place :</label>
        <select name="parking_id" id="parking_id" required>
            <option value="" disabled selected>-- Choisissez une place --</option>
            <?php foreach ($parkingsByType as $type => $places): ?>
                <?php if (!empty($places)): ?>
                    <optgroup label="<?= ucfirst($type) ?>">
                        <?php foreach ($places as $place): ?>
                            <option value="<?= $place['id'] ?>">
                                <?= htmlspecialchars("Place {$place['numero_place']} - Étage {$place['etage']}") ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <!-- Dates -->
        <label for="start_time">Début :</label>
        <input type="datetime-local" name="start_time" id="start_time" required>

        <label for="end_time">Fin :</label>
        <input type="datetime-local" name="end_time" id="end_time" required>

        <button type="submit">Réserver</button>
    </form>

    <?php if (isset($_GET['id'])): ?>
        <hr>
        <form method="GET" action="/index.php">
            <input type="hidden" name="page" value="paiement">
            <input type="hidden" name="id" value="<?= (int)$_GET['id'] ?>">
            <button type="submit">💳 Payer maintenant</button>
        </form>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
