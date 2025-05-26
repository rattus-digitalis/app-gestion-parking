<?php
$title = "Modifier la réservation #{$reservation['id']}";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container">
    <h1>✏️ Modifier la réservation #<?= htmlspecialchars($reservation['id']) ?></h1>

    <form method="POST" action="/?page=edit_reservation_admin">
        <input type="hidden" name="id" value="<?= htmlspecialchars($reservation['id']) ?>">

        <label>Utilisateur (ID)</label>
        <input type="number" name="user_id" value="<?= htmlspecialchars($reservation['user_id']) ?>" required>

        <label>ID de la place</label>
        <input type="number" name="parking_id" value="<?= htmlspecialchars($reservation['parking_id']) ?>" required>

        <label>Date de début</label>
        <input type="datetime-local" name="date_start"
               value="<?= date('Y-m-d\TH:i', strtotime($reservation['date_start'])) ?>" required>

        <label>Date de fin</label>
        <input type="datetime-local" name="date_end"
               value="<?= date('Y-m-d\TH:i', strtotime($reservation['date_end'])) ?>" required>

        <label>Statut</label>
        <select name="status">
            <option value="pending"   <?= $reservation['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
            <option value="active"    <?= $reservation['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="cancelled" <?= $reservation['status'] === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
        </select>

        <button type="submit">💾 Enregistrer les modifications</button>
    </form>

    <p><a href="/?page=reservations_list">← Retour à la liste des réservations</a></p>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
