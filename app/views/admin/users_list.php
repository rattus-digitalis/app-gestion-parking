<?php
$title = "Gestion des utilisateurs";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

// Gestion des messages flash simples (à définir dans le controller)
if (isset($_SESSION['flash_message'])): ?>
    <div class="flash-message <?= htmlspecialchars($_SESSION['flash_type'] ?? 'info') ?>">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
    </div>
<?php
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
endif;

// Pagination basique
$page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

// Slicing tableau utilisateurs (si pas fait en base)
$paginatedUsers = array_slice($users, $offset, $itemsPerPage);
$totalPages = ceil(count($users) / $itemsPerPage);
?>

<main>
    <h1>Liste des utilisateurs</h1>

    <p><a href="/?page=create_user" class="btn-create">+ Créer un utilisateur</a></p>

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
                <?php foreach ($paginatedUsers as $user): ?>
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

                            <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');" aria-label="Supprimer utilisateur <?= htmlspecialchars($user['last_name']) ?>">
                                <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn-delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination simple -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Pagination utilisateurs">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="<?= $i === $page ? 'active' : '' ?>">
                            <a href="/?page=admin_users&p=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <p><a href="/?page=dashboard_admin" class="btn-back">← Retour au dashboard admin</a></p>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
 