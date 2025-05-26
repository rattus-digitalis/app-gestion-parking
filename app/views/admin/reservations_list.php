<?php
$title = "Gestion des réservations";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container reservations-list">
    <header>
        <h1>Gestion des réservations</h1>
    </header>

    <?php if (empty($reservations)): ?>
        <p>Aucune réservation trouvée.</p>
    <?php else: ?>
        <section class="table-wrapper">
            <table class="data-table" role="grid" aria-label="Liste des réservations">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Utilisateur</th>
                        <th scope="col">Place de parking</th>
                        <th scope="col">Début</th>
                        <th scope="col">Fin</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Actions</th>
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
                                <a href="/?page=admin_edit_reservation&id=<?= urlencode($res['id']) ?>" class="btn-link btn-edit" aria-label="Modifier la réservation #<?= $res['id'] ?>">✏️ Modifier</a>
                                <form method="POST" action="/?page=admin_delete_reservation" style="display:inline;" onsubmit="return confirm('Supprimer cette réservation ?');" aria-label="Supprimer la réservation #<?= $res['id'] ?>">
                                    <input type="hidden" name="delete_reservation_id" value="<?= $res['id'] ?>">
                                    <button type="submit" class="btn-link btn-delete">🗑 Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>

    <footer>
        <a href="/?page=dashboard_admin" class="btn-link btn-back">Retour au tableau de bord</a>
    </footer>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
