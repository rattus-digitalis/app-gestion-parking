<?php
$title = "Nouvelle réservation";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Réserver une place</h1>

    <form method="POST" action="/?page=nouvelle_reservation">
        <label>Parking :</label><br>
        <select name="parking_id" required>
            <?php foreach ($parkings as $parking): ?>
                <option value="<?= $parking['id'] ?>">
                    <?= htmlspecialchars($parking['name']) ?> – <?= htmlspecialchars($parking['address']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Date de début :</label><br>
        <input type="datetime-local" name="start_time" required><br><br>

        <label>Date de fin :</label><br>
        <input type="datetime-local" name="end_time" required><br><br>

        <button type="submit">Réserver</button>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
