<?php
$title = "Gestion des places de parking";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container parking-list" role="main">
    <header>
        <h1>🚗 Liste des places de parking</h1>
    </header>

    <?php if (empty($parkings)): ?>
        <p class="alert alert-info">Aucune place de parking trouvée.</p>
    <?php else: ?>
        <section class="table-wrapper">
            <table class="data-table" role="grid" aria-label="Liste des places de parking">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Numéro</th>
                        <th scope="col">Étage</th>
                        <th scope="col">Type</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Disponible depuis</th>
                        <th scope="col">Dernière mise à jour</th>
                        <th scope="col">Commentaire</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($parkings as $parking): ?>
                        <tr>
                            <td><?= htmlspecialchars($parking['id']) ?></td>
                            <td><?= htmlspecialchars($parking['numero_place'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($parking['etage'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars(ucfirst($parking['type_place'] ?? 'Inconnu')) ?></td>
                            <td>
                                <form method="POST" class="status-form" aria-label="Modifier le statut de la place <?= htmlspecialchars($parking['numero_place'] ?? '') ?>">
                                    <input type="hidden" name="parking_id" value="<?= htmlspecialchars($parking['id']) ?>">
                                    <select name="status" onchange="this.form.submit()" aria-live="polite">
                                        <?php
                                        $statuses = [
                                            'libre' => 'Libre',
                                            'occupe' => 'Occupée',
                                            'reserve' => 'Réservée',
                                            'maintenance' => 'Maintenance'
                                        ];
                                        $currentStatus = $parking['statut'] ?? 'libre';
                                        foreach ($statuses as $key => $label):
                                            $selected = ($currentStatus === $key) ? 'selected' : '';
                                        ?>
                                            <option value="<?= htmlspecialchars($key) ?>" <?= $selected ?>><?= htmlspecialchars($label) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                            <td><?= htmlspecialchars($parking['disponible_depuis'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($parking['date_maj'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($parking['commentaire'] ?? '') ?></td>
                            <td>
                                <a href="/?page=edit_parking&id=<?= htmlspecialchars($parking['id']) ?>" class="btn btn-sm btn-warning" aria-label="Modifier la place <?= htmlspecialchars($parking['numero_place']) ?>">✏️</a>
                                <form method="POST" action="/?page=delete_parking" onsubmit="return confirm('Supprimer cette place ?');" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($parking['id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" aria-label="Supprimer la place <?= htmlspecialchars($parking['numero_place']) ?>">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>

    <footer class="footer-actions">
        <a href="/?page=dashboard_admin" class="btn-link btn-back">← Retour au tableau de bord</a>
        <a href="/?page=add_parking" class="btn btn-primary">➕ Ajouter une place</a>
    </footer>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
