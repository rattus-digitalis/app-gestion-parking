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
        if ($r['date_end'] > $now) {
            $actives[] = $r;
        } else {
            $past[] = $r;
        }
    }

    require __DIR__ . '/../views/pages/mes_reservations.php';
}
