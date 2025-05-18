<?php
$title = "Gestion des utilisateurs";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Liste des utilisateurs</h1>

    <?php if (empty($users)): ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php else: ?>
        <table class="users-table" role="grid" aria-label="Liste des utilisateurs">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Rôle</th>
                    <th scope="col">Statut</th>
                    <th scope="col" aria-label="Actions utilisateur">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['last_name']) ?></td>
                        <td><?= htmlspecialchars($user['first_name']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($user['email']) ?>"><?= htmlspecialchars($user['email']) ?></a></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                        <td>
                            <span class="status <?= htmlspecialchars($user['status']) ?>">
                                <?= htmlspecialchars(ucfirst($user['status'])) ?>
                            </span>
                        </td>
                        <td class="actions-cell">
                            <a href="/?page=edit_user&id=<?= urlencode($user['id']) ?>" class="btn-edit" aria-label="Modifier utilisateur <?= htmlspecialchars($user['last_name']) ?>">Modifier</a>

                            <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet utilisateur ?');" aria-label="Supprimer utilisateur <?= htmlspecialchars($user['last_name']) ?>">
                                <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
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
