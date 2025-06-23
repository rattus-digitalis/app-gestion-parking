<?php
$title = "Modifier la réservation #{$reservation['id']}";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container" role="main">
    <h1>✏️ Modifier la réservation #<?= htmlspecialchars($reservation['id']) ?></h1>

    <form method="POST" action="/?page=edit_reservation_admin" class="reservation-form">
        <input type="hidden" name="id" value="<?= htmlspecialchars($reservation['id']) ?>">

        <div class="form-group">
            <label for="user_id">Utilisateur (ID)</label>
            <input type="number" id="user_id" name="user_id" value="<?= htmlspecialchars($reservation['user_id']) ?>" required>
        </div>

        <div class="form-group">
            <label for="parking_id">ID de la place</label>
            <input type="number" id="parking_id" name="parking_id" value="<?= htmlspecialchars($reservation['parking_id']) ?>" required>
        </div>

        <div class="form-group">
            <label for="date_start">Date de début</label>
            <input type="datetime-local" id="date_start" name="date_start"
                   value="<?= date('Y-m-d\TH:i', strtotime($reservation['date_start'])) ?>" required>
        </div>

        <div class="form-group">
            <label for="date_end">Date de fin</label>
            <input type="datetime-local" id="date_end" name="date_end"
                   value="<?= date('Y-m-d\TH:i', strtotime($reservation['date_end'])) ?>" required>
        </div>

        <div class="form-group">
            <label for="status">Statut</label>
            <select name="status" id="status" required>
                <option value="pending"   <?= $reservation['status'] === 'pending' ? 'selected' : '' ?>>⏳ En attente</option>
                <option value="active"    <?= $reservation['status'] === 'active' ? 'selected' : '' ?>>✅ Active</option>
                <option value="cancelled" <?= $reservation['status'] === 'cancelled' ? 'selected' : '' ?>>❌ Annulée</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
            <a href="/?page=reservations_list" class="btn btn-secondary">← Retour à la liste</a>
        </div>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
