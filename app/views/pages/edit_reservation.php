<div class="card">
    <h2>Modifier la Réservation</h2>
    
    <form method="POST" action="/?page=update_reservation" class="edit-reservation-form">
        <input type="hidden" name="id" value="<?= htmlspecialchars($reservation['id']) ?>">
        
        <div class="form-group">
            <label for="parking_id" class="form-label">
                Place de parking *
                <span style="color: var(--text-muted); font-weight: normal;">(Sélectionnez une nouvelle place si nécessaire)</span>
            </label>
            <select name="parking_id" id="parking_id" class="form-select" required>
                <?php foreach ($parkings as $p): ?>
                    <option value="<?= htmlspecialchars($p['id']) ?>" <?= $p['id'] == $reservation['parking_id'] ? 'selected' : '' ?>>
                        Place <?= htmlspecialchars($p['numero_place']) ?> - Étage <?= htmlspecialchars($p['etage']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="start_time" class="form-label">
                Date et heure de début *
                <span style="color: var(--text-muted); font-weight: normal;">(Format: JJ/MM/AAAA HH:MM)</span>
            </label>
            <input type="datetime-local"
                   id="start_time"
                   name="start_time"
                   class="form-input"
                   required
                   value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($reservation['date_start']))) ?>">
        </div>

        <div class="form-group">
            <label for="end_time" class="form-label">
                Date et heure de fin *
                <span style="color: var(--text-muted); font-weight: normal;">(Format: JJ/MM/AAAA HH:MM)</span>
            </label>
            <input type="datetime-local"
                   id="end_time"
                   name="end_time"
                   class="form-input"
                   required
                   value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($reservation['date_end']))) ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                ✓ Enregistrer les modifications
            </button>
            <a href="/?page=mes_reservations" class="btn btn-secondary">
                ← Annuler
            </a>
        </div>
    </form>
</div>

<script>
// Validation côté client pour s'assurer que la date de fin est après la date de début
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const form = document.querySelector('.edit-reservation-form');
    
    function validateDates() {
        const startTime = new Date(startTimeInput.value);
        const endTime = new Date(endTimeInput.value);
        
        if (startTime >= endTime) {
            endTimeInput.setCustomValidity('La date de fin doit être postérieure à la date de début');
        } else {
            endTimeInput.setCustomValidity('');
        }
    }
    
    startTimeInput.addEventListener('change', validateDates);
    endTimeInput.addEventListener('change', validateDates);
    
    form.addEventListener('submit', function(e) {
        validateDates();
        if (!endTimeInput.checkValidity()) {
            e.preventDefault();
            endTimeInput.reportValidity();
        }
    });
});
</script>