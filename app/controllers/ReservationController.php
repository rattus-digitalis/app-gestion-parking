<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Parking.php';
require_once __DIR__ . '/../models/Car.php';
require_once __DIR__ . '/../models/Tarif.php';

class ReservationController
{
    // Méthode pour inclure les templates avec gestion des erreurs
    private function render($view, $data = [])
    {
        extract($data);
        
        include __DIR__ . '/../views/templates/head.php';
        include __DIR__ . '/../views/templates/nav.php';
        include __DIR__ . "/../views/$view.php";
        include __DIR__ . '/../views/templates/footer.php';
    }

    public function mesReservations()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $reservationModel = new Reservation();
        $all = $reservationModel->getByUserId($_SESSION['user']['id']);

        $now = date('Y-m-d H:i:s');
        $actives = [];
        $past = [];

        foreach ($all as $r) {
            if ($r['status'] === 'cancelled' || $r['date_end'] <= $now) {
                $past[] = $r;
            } else {
                $actives[] = $r;
            }
        }

        // Vérifier s'il y a un message de succès
        $success = '';
        if (isset($_GET['success']) && $_GET['success'] === 'paiement') {
            $success = '✅ Paiement validé avec succès !';
        }

        $this->render('pages/mes_reservations', [
            'actives' => $actives,
            'past' => $past,
            'success' => $success
        ]);
    }

    public function form()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $parkingModel = new Parking();
        $carModel = new Car();

        $types = ['standard', 'chargeur', 'moto', 'handicap'];

        $parkingsByType = [];
        foreach ($types as $type) {
            $parkingsByType[$type] = $parkingModel->getByType($type);
        }

        $car = $carModel->getByUserId($_SESSION['user']['id']);

        $this->render('pages/nouvelle_reservation', [
            'parkingsByType' => $parkingsByType,
            'car' => $car,
            'types' => $types
        ]);
    }

    public function cancelReservation()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            // Au lieu d'un simple echo, on affiche une page d'erreur
            $this->render('errors/400', [
                'error' => 'ID de réservation manquant.'
            ]);
            return;
        }

        $reservationModel = new Reservation();
        $reservationModel->cancel((int)$id);

        header('Location: /?page=mes_reservations');
        exit;
    }

    public function create(array $data)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $userId    = $_SESSION['user']['id'];
        $parkingId = $data['parking_id'] ?? null;
        $carId     = $data['car_id'] ?? null;
        $start     = $data['start_time'] ?? null;
        $end       = $data['end_time'] ?? null;

        // Gestion des erreurs avec retour sur le formulaire
        if (!$parkingId || !$carId || !$start || !$end) {
            $this->showFormWithError("❌ Tous les champs sont obligatoires.", $data);
            return;
        }

        $reservationModel = new Reservation();
        $parkingModel = new Parking();
        $tarifModel = new Tarif();

        if (!$reservationModel->isAvailable((int)$parkingId, $start, $end)) {
            $this->showFormWithError("❌ Ce créneau n'est pas disponible.", $data);
            return;
        }

        $parking = $parkingModel->getById((int)$parkingId);
        if (!$parking) {
            $this->showFormWithError("❌ Parking introuvable.", $data);
            return;
        }

        $typeVehicule = strtolower($parking['type_place']);

        $map = [
            'standard'  => 'voiture',
            'chargeur'  => 'voiture',
            'handicap'  => 'voiture',
            'moto'      => 'moto',
        ];

        if (!isset($map[$typeVehicule])) {
            $this->showFormWithError("❌ Type de place inconnu : $typeVehicule", $data);
            return;
        }

        $typeVehicule = $map[$typeVehicule];

        $tarifs = $tarifModel->getAll();

        if (!isset($tarifs[$typeVehicule])) {
            $this->showFormWithError("❌ Tarif non défini pour le type de véhicule : $typeVehicule", $data);
            return;
        }

        $typeTarif = 'horaire';
        $montantTarif = (float) $tarifs[$typeVehicule][$typeTarif === 'horaire' ? 'heure' : 'jour'];

        $montant = $this->calculerMontant($start, $end, $typeTarif, $montantTarif);

        $reservationId = $reservationModel->create(
            $userId,
            (int)$parkingId,
            $start,
            $end,
            'pending',
            (int)$carId
        );

        if (!$reservationId) {
            $this->showFormWithError("❌ Erreur lors de la création de la réservation.", $data);
            return;
        }

        header("Location: /?page=paiement&id=$reservationId&montant=$montant");
        exit;
    }

    // Nouvelle méthode pour afficher le formulaire avec erreur
    private function showFormWithError($error, $oldData = [])
    {
        $parkingModel = new Parking();
        $carModel = new Car();

        $types = ['standard', 'chargeur', 'moto', 'handicap'];

        $parkingsByType = [];
        foreach ($types as $type) {
            $parkingsByType[$type] = $parkingModel->getByType($type);
        }

        $car = $carModel->getByUserId($_SESSION['user']['id']);

        $this->render('pages/nouvelle_reservation', [
            'parkingsByType' => $parkingsByType,
            'car' => $car,
            'types' => $types,
            'error' => $error,
            'old_data' => $oldData
        ]);
    }

    public function editForm(int $reservationId)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $reservationModel = new Reservation();
        $reservation = $reservationModel->getReservationById($reservationId);

        if (!$reservation || $reservation['user_id'] != $_SESSION['user']['id']) {
            $this->render('errors/403', [
                'error' => 'Accès refusé.'
            ]);
            return;
        }

        $parkingModel = new Parking();
        $parkings = $parkingModel->getAllParkings();

        $this->render('pages/edit_reservation', [
            'reservation' => $reservation,
            'parkings' => $parkings
        ]);
    }

    public function update(array $data)
    {
        $reservationModel = new Reservation();

        $reservationId = (int)$data['id'];
        $userId = $_SESSION['user']['id'];
        $parkingId = (int)$data['parking_id'];
        $start = $data['start_time'];
        $end = $data['end_time'];

        if (!$reservationModel->isAvailable($parkingId, $start, $end, $reservationId)) {
            // Réafficher le formulaire d'édition avec l'erreur
            $reservation = $reservationModel->getReservationById($reservationId);
            $parkingModel = new Parking();
            $parkings = $parkingModel->getAllParkings();

            $this->render('pages/edit_reservation', [
                'reservation' => $reservation,
                'parkings' => $parkings,
                'error' => '❌ La place n\'est pas disponible à ce créneau.',
                'old_data' => $data
            ]);
            return;
        }

        $reservationModel->updateReservation($reservationId, [
            'user_id' => $userId,
            'parking_id' => $parkingId,
            'date_start' => $start,
            'date_end' => $end,
            'status' => 'pending',
            'car_id' => null
        ]);

        header('Location: /?page=mes_reservations');
        exit;
    }

    public function validerPaiement()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $reservationId = $_GET['id'] ?? null;
        if (!$reservationId) {
            $this->render('errors/400', [
                'error' => '❌ ID de réservation manquant.'
            ]);
            return;
        }

        $reservationModel = new Reservation();
        $reservationModel->marquerCommePayee((int)$reservationId);

        header('Location: /?page=mes_reservations&success=paiement');
        exit;
    }

    private function calculerMontant(string $start, string $end, string $type, float $tarif): float
    {
        $debut = new DateTimeImmutable($start);
        $fin = new DateTimeImmutable($end);

        if ($type === 'horaire') {
            $diffHeures = ($fin->getTimestamp() - $debut->getTimestamp()) / 3600;
            return round($tarif * $diffHeures, 2);
        } else {
            $diffJours = ceil(($fin->getTimestamp() - $debut->getTimestamp()) / 86400);
            return round($tarif * $diffJours, 2);
        }
    }
}