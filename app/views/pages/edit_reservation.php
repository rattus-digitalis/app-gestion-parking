<form method="POST" action="/?page=update_reservation" class="edit-reservation-form">
    <input type="hidden" name="id" value="<?= htmlspecialchars($reservation['id']) ?>">

    <div class="form-group">
        <label for="parking_id">Place *</label>
        <select name="parking_id" id="parking_id" required>
            <?php foreach ($parkings as $p): ?>
                <option value="<?= htmlspecialchars($p['id']) ?>" <?= $p['id'] == $reservation['parking_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars("Place {$p['numero_place']} - Étage {$p['etage']}") ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="start_time">Date de début *</label>
        <input type="datetime-local"
               id="start_time"
               name="start_time"
               required
               value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($reservation['date_start']))) ?>">
    </div>

    <div class="form-group">
        <label for="end_time">Date de fin *</label>
        <input type="datetime-local"
               id="end_time"
               name="end_time"
               required
               value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($reservation['date_end']))) ?>">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="/?page=mes_reservations" class="btn btn-secondary">← Annuler</a>
    </div>
</form>
