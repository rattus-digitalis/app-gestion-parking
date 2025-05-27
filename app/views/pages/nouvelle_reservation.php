<?php
$title = "Nouvelle réservation";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container new-reservation" role="main">
    <h1> Nouvelle réservation</h1>

    <form method="POST" action="/?page=nouvelle_reservation" aria-label="Formulaire nouvelle réservation">
        <!-- Voiture -->
        <div class="form-group">
            <label for="car_display">Votre voiture</label>
            <input type="text" id="car_display" name="car_display"
                value="<?= isset($car) 
                    ? htmlspecialchars(($car['marque'] ?? '') . ' ' . ($car['modele'] ?? '') . ' (' . ($car['immatriculation'] ?? '') . ')') 
                    : 'Aucune voiture enregistrée' 
                ?>" 
                readonly aria-readonly="true">
        </div>

        <?php if (isset($car['id'])): ?>
            <input type="hidden" name="car_id" value="<?= htmlspecialchars($car['id']) ?>">
        <?php endif; ?>

        <!-- Choix de la place -->
        <div class="form-group">
            <label for="parking_id">Choisissez votre place</label>
            <select name="parking_id" id="parking_id" required>
                <option value="" disabled selected>-- Choisissez une place --</option>
                <?php foreach ($parkingsByType as $type => $places): ?>
                    <?php if (!empty($places)): ?>
                        <optgroup label="<?= htmlspecialchars(ucfirst($type)) ?>">
                            <?php foreach ($places as $place): ?>
                                <option value="<?= htmlspecialchars($place['id']) ?>">
                                    <?= htmlspecialchars("Place {$place['numero_place']} - Étage {$place['etage']}") ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Dates -->
        <div class="form-group">
            <label for="start_time">Début</label>
            <input type="datetime-local" name="start_time" id="start_time" required>
        </div>

        <div class="form-group">
            <label for="end_time">Fin</label>
            <input type="datetime-local" name="end_time" id="end_time" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Réserver</button>
        </div>
    </form>

    <?php if (isset($_GET['id'])): ?>
        <hr>
        <form method="GET" action="/index.php" aria-label="Formulaire paiement réservation">
            <input type="hidden" name="page" value="paiement">
            <input type="hidden" name="id" value="<?= (int)$_GET['id'] ?>">
            <button type="submit" class="btn btn-secondary">Payer maintenant</button>
        </form>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
