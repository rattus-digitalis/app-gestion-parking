<form method="POST" action="/?page=update_reservation">
    <input type="hidden" name="id" value="<?= $reservation['id'] ?>">

    <label>Place :</label>
    <select name="parking_id">
        <?php foreach ($parkings as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $p['id'] == $reservation['parking_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars("Place {$p['numero_place']} - Étage {$p['etage']}") ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Date début :</label>
    <input type="datetime-local" name="start_time" value="<?= date('Y-m-d\TH:i', strtotime($reservation['date_start'])) ?>">

    <label>Date fin :</label>
    <input type="datetime-local" name="end_time" value="<?= date('Y-m-d\TH:i', strtotime($reservation['date_end'])) ?>">

    <button type="submit">Modifier</button>
</form>
