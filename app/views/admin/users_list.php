<?php
$title = "Gestion des utilisateurs";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';

// Message flash
if (isset($_SESSION['flash_message'])): ?>
    <div class="flash-message <?= htmlspecialchars($_SESSION['flash_type'] ?? 'info') ?>" role="alert">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
    </div>
<?php
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
endif;

// Pagination
$page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;
$paginatedUsers = array_slice($users, $offset, $itemsPerPage);
$totalPages = ceil(count($users) / $itemsPerPage);
?>

<main class="container users-list" role="main">
    <header>
        <h1>👥 Gestion des utilisateurs</h1>
    </header>

    <p>
        <a href="/?page=create_user" class="btn btn-primary">➕ Créer un utilisateur</a>
    </p>

    <?php if (empty($users)): ?>
        <p class="alert alert-info">Aucun utilisateur trouvé.</p>
    <?php else: ?>
        <section class="table-wrapper">
            <table class="data-table" role="grid" aria-label="Liste des utilisateurs">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Email</th>
                        <th scope="col">Téléphone</th>
                        <th scope="col">Rôle</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paginatedUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['last_name']) ?></td>
                            <td><?= htmlspecialchars($user['first_name']) ?></td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($user['email']) ?>">
                                    <?= htmlspecialchars($user['email']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($user['phone']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                            <td class="actions-cell">
                                <a href="/?page=edit_user&id=<?= urlencode($user['id']) ?>"
                                   class="btn btn-sm btn-warning"
                                   aria-label="Modifier l'utilisateur <?= htmlspecialchars($user['last_name']) ?>">✏️</a>

                                <form method="POST"
                                      action="/?page=admin_delete_user"
                                      onsubmit="return confirm('Supprimer cet utilisateur ?');"
                                      style="display:inline;">
                                    <input type="hidden" name="delete_user_id" value="<?= htmlspecialchars($user['id']) ?>">
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            aria-label="Supprimer l'utilisateur <?= htmlspecialchars($user['last_name']) ?>">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Pagination utilisateurs" class="pagination-nav">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="<?= $i === $page ? 'active' : '' ?>">
                            <a href="/?page=admin_users&p=<?= $i ?>"
                               aria-current="<?= $i === $page ? 'page' : 'false' ?>">
                               <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <footer class="footer-actions">
        <a href="/?page=dashboard_admin" class="btn btn-secondary">← Retour au tableau de bord</a>
    </footer>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
