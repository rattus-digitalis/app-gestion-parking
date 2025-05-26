<?php
$title = "Mes réservations";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main>
    <h1>Mes réservations</h1>

    <?php if (empty($actives) && empty($past)) : ?>
        <p>Vous n'avez encore effectué aucune réservation.</p>
    <?php endif; ?>

    <?php if (!empty($actives)) : ?>
        <section>
            <h2>🔒 Réservations actives</h2>
            <ul>
                <?php foreach ($actives as $res) : ?>
                    <li>
                        <strong>Place <?= htmlspecialchars($res['numero_place']) ?> (Étage <?= htmlspecialchars($res['etage']) ?>)</strong><br>
                        Du <time><?= date('d/m/Y H:i', strtotime($res['date_start'])) ?></time>
                        au <time><?= date('d/m/Y H:i', strtotime($res['date_end'])) ?></time><br>
                        Statut : <em><?= htmlspecialchars($res['status']) ?></em>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if (!empty($past)) : ?>
        <section>
            <h2>📜 Historique des réservations</h2>
            <ul>
                <?php foreach ($past as $res) : ?>
                    <li>
                        <strong>Place <?= htmlspecialchars($res['numero_place']) ?> (Étage <?= htmlspecialchars($res['etage']) ?>)</strong><br>
                        Du <time><?= date('d/m/Y H:i', strtotime($res['date_start'])) ?></time>
                        au <time><?= date('d/m/Y H:i', strtotime($res['date_end'])) ?></time><br>
                        Statut : <em><?= htmlspecialchars($res['status']) ?></em>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
