<?php
$title = "Mes réservations";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container user-reservations" role="main">
    <h1> Mes réservations</h1>

    <?php if (empty($actives) && empty($past)) : ?>
        <p class="alert alert-info" role="alert">Vous n'avez encore effectué aucune réservation.</p>
    <?php endif; ?>

    <?php if (!empty($actives)) : ?>
        <section class="active-reservations" aria-label="Réservations actives">
            <h2>Réservations actives</h2>

            <?php foreach ($actives as $res) : ?>
                <article class="reservation-card" aria-labelledby="reservation-<?= $res['id'] ?>">
                    <h3 id="reservation-<?= $res['id'] ?>">Réservation #<?= htmlspecialchars($res['id']) ?></h3>
                    <ul>
                        <li><strong>Place :</strong> <?= htmlspecialchars($res['numero_place']) ?></li>
                        <li><strong>Étage :</strong> <?= htmlspecialchars($res['etage']) ?></li>
                        <li><strong>Début :</strong> <time datetime="<?= date('c', strtotime($res['date_start'])) ?>"><?= date('d/m/Y H:i', strtotime($res['date_start'])) ?></time></li>
                        <li><strong>Fin :</strong> <time datetime="<?= date('c', strtotime($res['date_end'])) ?>"><?= date('d/m/Y H:i', strtotime($res['date_end'])) ?></time></li>
                        <li><strong>Statut :</strong> <em><?= htmlspecialchars($res['status']) ?></em></li>
                    </ul>

                    <?php if ($res['status'] !== 'cancelled') : ?>
                        <div class="reservation-actions">
                            <a href="/?page=annuler_reservation&id=<?= urlencode($res['id']) ?>"
                               class="btn btn-danger"
                               onclick="return confirm('Voulez-vous vraiment annuler cette réservation ?');"
                               aria-label="Annuler la réservation numéro <?= htmlspecialchars($res['id']) ?>">❌ Annuler</a>

                            <a href="/?page=modifier_reservation&id=<?= urlencode($res['id']) ?>"
                               class="btn btn-warning"
                               aria-label="Modifier la réservation numéro <?= htmlspecialchars($res['id']) ?>">✏️ Modifier</a>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if (!empty($past)) : ?>
        <section class="past-reservations" aria-label="Historique des réservations">
            <h2>Historique des réservations</h2>

            <?php foreach ($past as $res) : ?>
                <article class="reservation-card" aria-labelledby="past-reservation-<?= $res['id'] ?>">
                    <h3 id="past-reservation-<?= $res['id'] ?>">Réservation #<?= htmlspecialchars($res['id']) ?></h3>
                    <ul>
                        <li><strong>Place :</strong> <?= htmlspecialchars($res['numero_place']) ?></li>
                        <li><strong>Étage :</strong> <?= htmlspecialchars($res['etage']) ?></li>
                        <li><strong>Début :</strong> <time datetime="<?= date('c', strtotime($res['date_start'])) ?>"><?= date('d/m/Y H:i', strtotime($res['date_start'])) ?></time></li>
                        <li><strong>Fin :</strong> <time datetime="<?= date('c', strtotime($res['date_end'])) ?>"><?= date('d/m/Y H:i', strtotime($res['date_end'])) ?></time></li>
                        <li><strong>Statut :</strong> <em><?= htmlspecialchars($res['status']) ?></em></li>
                    </ul>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
