<?php
$title = "Gestion des places de parking";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Liste des places de parking</h1>

    <?php if (empty($parkings)): ?>
        <p>Aucune place de parking trouvée.</p>
    <?php else: ?>
        <table class="users-table" role="grid" aria-label="Liste des places de parking">
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
                        <td><?= htmlspecialchars(ucfirst($parking['type_place'] ?? 'inconnu')) ?></td>
                        <td>
                            <form method="POST" style="margin:0;" aria-label="Modifier le statut de la place <?= htmlspecialchars($parking['numero_place'] ?? '') ?>">
                                <input type="hidden" name="parking_id" value="<?= $parking['id'] ?>">
                                <select name="status" onchange="this.form.submit()" aria-live="polite" aria-atomic="true" aria-relevant="all">
                                    <?php
                                    $statuses = [
                                        'libre' => 'Libre',
                                        'occupe' => 'Occupée',
                                        'reserve' => 'Réservée'
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
                            <!-- Tu peux ajouter ici des boutons d'actions si besoin -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="/?page=dashboard_admin" class="btn-back">← Retour au dashboard admin</a></p>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
