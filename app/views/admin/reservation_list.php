<?php
$title = "Gestion des réservations";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Liste des réservations</h1>

    <?php if (empty($reservations)): ?>
        <p>Aucune réservation trouvée.</p>
    <?php else: ?>
        <table class="users-table" role="grid" aria-label="Liste des réservations">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Utilisateur</th>
                    <th scope="col">Place de parking</th>
                    <th scope="col">Début</th>
                    <th scope="col">Fin</th>
                    <th scope="col">Statut</th>
                    <th scope="col" aria-label="Actions réservation">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $res): ?>
                    <tr>
                        <td><?= htmlspecialchars($res['id']) ?></td>
                        <td><?= htmlspecialchars($res['first_name'] . ' ' . $res['last_name']) ?></td>
                        <td><?= htmlspecialchars($res['numero_place']) ?></td>
                        <td><?= htmlspecialchars($res['date_start']) ?></td>
                        <td><?= htmlspecialchars($res['date_end']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($res['status'])) ?></td>
                        <td class="actions-cell">
                            <a href="/?page=edit_reservation&id=<?= urlencode($res['id']) ?>" class="btn-edit" aria-label="Modifier réservation #<?= $res['id'] ?>">Modifier</a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette réservation ?');" aria-label="Supprimer réservation #<?= $res['id'] ?>">
                                <input type="hidden" name="delete_reservation_id" value="<?= $res['id'] ?>">
                                <button type="submit" class="btn-delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="/?page=dashboard_admin" class="btn-back">← Retour au dashboard admin</a></p>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
