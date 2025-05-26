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
            <?php foreach ($actives as $res) : ?>
                <article style="margin-bottom: 1.5em;">
                    <p>
                        <strong>Place :</strong> <?= htmlspecialchars($res['numero_place']) ?><br>
                        <strong>Étage :</strong> <?= htmlspecialchars($res['etage']) ?><br>
                        <strong>Début :</strong> <time><?= date('d/m/Y H:i', strtotime($res['date_start'])) ?></time><br>
                        <strong>Fin :</strong> <time><?= date('d/m/Y H:i', strtotime($res['date_end'])) ?></time><br>
                        <strong>Statut :</strong> <em><?= htmlspecialchars($res['status']) ?></em>
                    </p>

                    <?php if ($res['status'] !== 'cancelled') : ?>
                        <p>
                            <a href="/?page=annuler_reservation&id=<?= urlencode($res['id']) ?>"
                               onclick="return confirm('Voulez-vous vraiment annuler cette réservation ?');">
                                ❌ Annuler
                            </a>
                            &nbsp;|&nbsp;
                            <a href="/?page=modifier_reservation&id=<?= urlencode($res['id']) ?>">
                                ✏️ Modifier
                            </a>
                        </p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if (!empty($past)) : ?>
        <section>
            <h2>📜 Historique des réservations</h2>
            <?php foreach ($past as $res) : ?>
                <article style="margin-bottom: 1.5em;">
                    <p>
                        <strong>Place :</strong> <?= htmlspecialchars($res['numero_place']) ?><br>
                        <strong>Étage :</strong> <?= htmlspecialchars($res['etage']) ?><br>
                        <strong>Début :</strong> <time><?= date('d/m/Y H:i', strtotime($res['date_start'])) ?></time><br>
                        <strong>Fin :</strong> <time><?= date('d/m/Y H:i', strtotime($res['date_end'])) ?></time><br>
                        <strong>Statut :</strong> <em><?= htmlspecialchars($res['status']) ?></em>
                    </p>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
