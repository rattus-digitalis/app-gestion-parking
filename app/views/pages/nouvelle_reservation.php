<?php
$title = "Nouvelle réservation";
require_once __DIR__ . '/../templates/head.php';
require_once __DIR__ . '/../templates/nav.php';
?>

<main class="container new-reservation" role="main">
    <header class="text-center mb-5">
        <h1 class="section-title">Nouvelle réservation</h1>
        <p class="text-secondary">Sélectionnez votre place et définissez votre période de stationnement</p>
    </header>

    <div class="card">
        <form method="POST" action="/?page=nouvelle_reservation" aria-labelledby="form-title" novalidate>
            <!-- Voiture -->
            <div class="form-group">
                <label for="car_display" class="form-label">Votre véhicule</label>
                <input type="text" 
                       id="car_display" 
                       name="car_display"
                       class="form-input"
                       value="<?= isset($car) 
                           ? htmlspecialchars(($car['marque'] ?? '') . ' ' . ($car['modele'] ?? '') . ' (' . ($car['immatriculation'] ?? '') . ')') 
                           : 'Aucune voiture enregistrée' 
                       ?>" 
                       readonly 
                       aria-readonly="true"
                       aria-describedby="car-help">
                
                <?php if (!isset($car)): ?>
                    <div id="car-help" class="alert alert-warning mt-2">
                        Veuillez d'abord enregistrer une voiture dans votre profil pour pouvoir effectuer une réservation.
                    </div>
                <?php else: ?>
                    <small id="car-help" class="form-text text-muted">Votre véhicule enregistré</small>
                <?php endif; ?>
            </div>

            <?php if (isset($car['id'])): ?>
                <input type="hidden" name="car_id" value="<?= htmlspecialchars($car['id']) ?>" aria-hidden="true">
            <?php endif; ?>

            <!-- Choix de la place -->
            <div class="form-group">
                <label for="parking_id" class="form-label">
                    Choisissez votre place 
                    <span class="text-danger">*</span>
                </label>
                <select name="parking_id" 
                        id="parking_id" 
                        class="form-select"
                        required 
                        aria-describedby="parking-help"
                        aria-invalid="false">
                    <option value="" disabled selected>-- Sélectionnez une place de parking --</option>
                    <?php foreach ($parkingsByType as $type => $places): ?>
                        <?php if (!empty($places)): ?>
                            <optgroup label="<?= htmlspecialchars(ucfirst($type)) ?>">
                                <?php foreach ($places as $place): ?>
                                    <option value="<?= htmlspecialchars($place['id']) ?>">
                                        Place <?= htmlspecialchars($place['numero_place']) ?> - Étage <?= htmlspecialchars($place['etage']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <small id="parking-help" class="form-text text-muted">
                    Sélectionnez la place qui vous convient selon le type et l'étage
                </small>
            </div>

            <!-- Période de réservation -->
            <fieldset class="card mt-4">
                <legend class="form-label">Période de réservation</legend>
                
                <div class="reservation-dates">
                    <div class="form-group">
                        <label for="start_time" class="form-label">
                            Date et heure de début 
                            <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="start_time" 
                               id="start_time" 
                               class="form-input"
                               required
                               aria-describedby="start-help"
                               aria-invalid="false">
                        <small id="start-help" class="form-text text-muted">
                            Début de votre réservation
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="form-label">
                            Date et heure de fin 
                            <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="end_time" 
                               id="end_time" 
                               class="form-input"
                               required
                               aria-describedby="end-help"
                               aria-invalid="false">
                        <small id="end-help" class="form-text text-muted">
                            Fin de votre réservation
                        </small>
                    </div>
                </div>
            </fieldset>

            <div class="form-actions text-center mt-5">
                <button type="submit" class="btn btn-primary btn-lg">
                    Confirmer la réservation
                </button>
            </div>
        </form>
    </div>

    <?php if (isset($_GET['id'])): ?>
        <div class="card mt-4">
            <div class="alert alert-success">
                <h3 class="mb-2">Réservation créée avec succès</h3>
                <p class="mb-3">
                    Votre place a été réservée. Vous pouvez maintenant procéder au paiement pour finaliser votre réservation.
                </p>
            </div>
            
            <form method="GET" action="/index.php" class="text-center">
                <input type="hidden" name="page" value="paiement" aria-hidden="true">
                <input type="hidden" name="id" value="<?= (int)$_GET['id'] ?>" aria-hidden="true">
                
                <button type="submit" class="btn btn-success btn-lg">
                    Payer maintenant
                </button>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>