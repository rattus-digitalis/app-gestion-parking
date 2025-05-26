<?php
$title = "Gestion des tarifs";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container" role="main">
    <header>
        <h1>💰 Gestion des tarifs</h1>
    </header>

    <form method="POST" action="/?page=admin_tarifs" class="tarif-form">
        <div class="table-wrapper">
            <table class="data-table" role="table" aria-label="Table de gestion des tarifs">
                <thead>
                    <tr>
                        <th scope="col">Type de véhicule</th>
                        <th scope="col">Tarif / heure (€)</th>
                        <th scope="col">Tarif / jour (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $types = ['voiture' => 'Voiture', 'moto' => 'Moto'];
                    foreach ($types as $typeKey => $typeLabel): ?>
                        <tr>
                            <th scope="row"><?= $typeLabel ?></th>
                            <td>
                                <label for="tarif_<?= $typeKey ?>_heure" class="visually-hidden">Tarif heure pour <?= $typeLabel ?></label>
                                <input type="number" step="0.01" min="0"
                                       name="tarifs[<?= $typeKey ?>][heure]"
                                       id="tarif_<?= $typeKey ?>_heure"
                                       value="<?= htmlspecialchars($tarifs[$typeKey]['heure'] ?? '') ?>" required>
                            </td>
                            <td>
                                <label for="tarif_<?= $typeKey ?>_jour" class="visually-hidden">Tarif jour pour <?= $typeLabel ?></label>
                                <input type="number" step="0.01" min="0"
                                       name="tarifs[<?= $typeKey ?>][jour]"
                                       id="tarif_<?= $typeKey ?>_jour"
                                       value="<?= htmlspecialchars($tarifs[$typeKey]['jour'] ?? '') ?>" required>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Enregistrer les tarifs</button>
            <a href="/?page=dashboard_admin" class="btn btn-secondary">← Retour au tableau de bord</a>
        </div>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
