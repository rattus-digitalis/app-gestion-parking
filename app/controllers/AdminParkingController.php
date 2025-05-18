<?php
require_once __DIR__ . '/../models/Parking.php';

class AdminParkingController
{
    public function listParkings()
    {
        $parkingModel = new Parking();
        $parkings = $parkingModel->getAllParkings();
        require_once __DIR__ . '/../views/admin/parkings_list.php';
    }

    public function updateStatus($postData)
    {
        if (!isset($postData['parking_id'], $postData['status'])) {
            header('Location: /?page=admin_parkings');
            exit;
        }

        $id = (int)$postData['parking_id'];
        $status = htmlspecialchars($postData['status']);

        $parkingModel = new Parking();
        $parkingModel->updateStatus($id, $status);

        header('Location: /?page=admin_parkings');
        exit;
    }
}

