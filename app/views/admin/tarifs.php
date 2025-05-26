<?php
$title = "Gestion des tarifs";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container">
    <h1>Gestion des tarifs</h1>

    <form method="POST" action="/?page=admin_tarifs">
        <table>
            <thead>
                <tr>
                    <th>Type de véhicule</th>
                    <th>Tarif / heure (€)</th>
                    <th>Tarif / jour (€)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Voiture</td>
                    <td>
                        <input type="number" step="0.01" name="tarifs[voiture][heure]"
                               value="<?= htmlspecialchars($tarifs['voiture']['heure'] ?? '') ?>" required>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="tarifs[voiture][jour]"
                               value="<?= htmlspecialchars($tarifs['voiture']['jour'] ?? '') ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Moto</td>
                    <td>
                        <input type="number" step="0.01" name="tarifs[moto][heure]"
                               value="<?= htmlspecialchars($tarifs['moto']['heure'] ?? '') ?>" required>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="tarifs[moto][jour]"
                               value="<?= htmlspecialchars($tarifs['moto']['jour'] ?? '') ?>" required>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="submit">💾 Enregistrer les tarifs</button>
    </form>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
