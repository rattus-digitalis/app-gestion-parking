<?php

require_once __DIR__ . '/../models/Car.php';

class CarController
{
    public function form()
    {
        $carModel = new Car();
        $car = $carModel->getByUserId($_SESSION['user']['id']);
        require_once __DIR__ . '/../views/pages/ma_voiture.php';
    }

    public function save(array $data)
    {
        $carModel = new Car();

        $marque = trim($data['marque'] ?? '');
        $modele = trim($data['modele'] ?? '');
        $immat  = trim($data['immatriculation'] ?? '');
        $couleur = trim($data['couleur'] ?? '');

        $carModel->save($_SESSION['user']['id'], $marque, $modele, $immat, $couleur);

        header("Location: /?page=ma_voiture");
        exit;
    }
}
