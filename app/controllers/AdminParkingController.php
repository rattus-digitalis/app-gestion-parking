<?php
require_once __DIR__ . '/../models/Parking.php';

class AdminParkingController
{
    public function listParkings()
    {
        $parkingModel = new Parking();
        $parkings = $parkingModel->getAllParkings();

        // Debug : décommenter pour voir les données récupérées
        // var_dump($parkings);
        // exit;

        require_once __DIR__ . '/../views/admin/parkings_list.php';
    }

    public function updateStatus(array $postData)
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

    /**
     * Affiche le formulaire d'édition d'un parking
     */
    public function editParkingForm(int $id): void 
    {
        try {
            $parkingModel = new Parking();
            $parking = $parkingModel->getById($id);
            
            if (!$parking) {
                $_SESSION['error'] = "Parking non trouvé.";
                header('Location: /?page=admin_parkings');
                exit;
            }
            
            // Récupérer les erreurs et messages de session
            $errors = $_SESSION['errors'] ?? [];
            $success = $_SESSION['success'] ?? null;
            
            // Nettoyer les messages de session
            unset($_SESSION['errors'], $_SESSION['success']);
            
            // Passer les données à la vue
            require_once __DIR__ . '/../views/admin/edit_parking.php';
            
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération du parking : " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement du parking.";
            header('Location: /?page=admin_parkings');
            exit;
        }
    }

    /**
     * Met à jour un parking
     */
    public function updateParking(array $data): void 
    {
        try {
            $id = $data['id'] ?? $_GET['id'] ?? null;
            
            if (!$id || !is_numeric($id)) {
                $_SESSION['error'] = "ID de parking invalide.";
                header('Location: /?page=admin_parkings');
                exit;
            }
            
            // Validation des données
            $errors = [];
            
            if (empty($data['nom'])) {
                $errors[] = "Le nom du parking est obligatoire.";
            }
            
            if (empty($data['adresse'])) {
                $errors[] = "L'adresse est obligatoire.";
            }
            
            if (empty($data['ville'])) {
                $errors[] = "La ville est obligatoire.";
            }
            
            if (empty($data['code_postal'])) {
                $errors[] = "Le code postal est obligatoire.";
            }
            
            if (empty($data['places_totales']) || !is_numeric($data['places_totales']) || $data['places_totales'] < 1) {
                $errors[] = "Le nombre de places totales doit être un nombre positif.";
            }
            
            if (!isset($data['places_disponibles']) || !is_numeric($data['places_disponibles']) || $data['places_disponibles'] < 0) {
                $errors[] = "Le nombre de places disponibles doit être un nombre positif ou nul.";
            }
            
            if (isset($data['places_totales'], $data['places_disponibles']) && 
                $data['places_disponibles'] > $data['places_totales']) {
                $errors[] = "Les places disponibles ne peuvent pas dépasser les places totales.";
            }
            
            if (empty($data['tarif_horaire']) || !is_numeric($data['tarif_horaire']) || $data['tarif_horaire'] < 0) {
                $errors[] = "Le tarif horaire doit être un nombre positif.";
            }
            
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: /?page=edit_parking&id=" . urlencode($id));
                exit;
            }
            
            // Préparation des données pour la mise à jour
            $parkingData = [
                'id' => (int)$id,
                'nom' => trim($data['nom']),
                'adresse' => trim($data['adresse']),
                'ville' => trim($data['ville']),
                'code_postal' => trim($data['code_postal']),
                'places_totales' => (int)$data['places_totales'],
                'places_disponibles' => (int)$data['places_disponibles'],
                'tarif_horaire' => (float)$data['tarif_horaire'],
                'description' => trim($data['description'] ?? ''),
                'actif' => isset($data['actif']) ? 1 : 0
            ];
            
            $parkingModel = new Parking();
            if ($parkingModel->updateParking($parkingData)) {
                $_SESSION['success'] = "Parking mis à jour avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du parking.";
            }
            
            header("Location: /?page=edit_parking&id=" . urlencode($id));
            exit;
            
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du parking : " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de la mise à jour du parking.";
            header('Location: /?page=admin_parkings');
            exit;
        }
    }

    /**
     * Affiche le formulaire de création d'un parking
     */
    public function createParkingForm(): void 
    {
        // Initialiser un parking vide pour le formulaire
        $parking = [
            'nom' => '',
            'adresse' => '',
            'ville' => '',
            'code_postal' => '',
            'places_totales' => '',
            'places_disponibles' => '',
            'tarif_horaire' => '',
            'description' => '',
            'actif' => 1
        ];
        
        // Récupérer les erreurs et messages de session
        $errors = $_SESSION['errors'] ?? [];
        $success = $_SESSION['success'] ?? null;
        
        // Nettoyer les messages de session
        unset($_SESSION['errors'], $_SESSION['success']);
        
        require_once __DIR__ . '/../views/admin/create_parking.php';
    }

    /**
     * Crée un nouveau parking
     */
    public function createParking(array $data): void 
    {
        try {
            // Validation des données (même validation que pour update)
            $errors = [];
            
            if (empty($data['nom'])) {
                $errors[] = "Le nom du parking est obligatoire.";
            }
            
            if (empty($data['adresse'])) {
                $errors[] = "L'adresse est obligatoire.";
            }
            
            if (empty($data['ville'])) {
                $errors[] = "La ville est obligatoire.";
            }
            
            if (empty($data['code_postal'])) {
                $errors[] = "Le code postal est obligatoire.";
            }
            
            if (empty($data['places_totales']) || !is_numeric($data['places_totales']) || $data['places_totales'] < 1) {
                $errors[] = "Le nombre de places totales doit être un nombre positif.";
            }
            
            if (!isset($data['places_disponibles']) || !is_numeric($data['places_disponibles']) || $data['places_disponibles'] < 0) {
                $errors[] = "Le nombre de places disponibles doit être un nombre positif ou nul.";
            }
            
            if (isset($data['places_totales'], $data['places_disponibles']) && 
                $data['places_disponibles'] > $data['places_totales']) {
                $errors[] = "Les places disponibles ne peuvent pas dépasser les places totales.";
            }
            
            if (empty($data['tarif_horaire']) || !is_numeric($data['tarif_horaire']) || $data['tarif_horaire'] < 0) {
                $errors[] = "Le tarif horaire doit être un nombre positif.";
            }
            
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: /?page=create_parking");
                exit;
            }
            
            // Création du parking
            $parkingData = [
                'nom' => trim($data['nom']),
                'adresse' => trim($data['adresse']),
                'ville' => trim($data['ville']),
                'code_postal' => trim($data['code_postal']),
                'places_totales' => (int)$data['places_totales'],
                'places_disponibles' => (int)$data['places_disponibles'],
                'tarif_horaire' => (float)$data['tarif_horaire'],
                'description' => trim($data['description'] ?? ''),
                'actif' => isset($data['actif']) ? 1 : 0
            ];
            
            $parkingModel = new Parking();
            if ($parkingModel->createParking($parkingData)) {
                $_SESSION['success'] = "Parking créé avec succès.";
                header('Location: /?page=admin_parkings');
            } else {
                $_SESSION['error'] = "Erreur lors de la création du parking.";
                header("Location: /?page=create_parking");
            }
            exit;
            
        } catch (Exception $e) {
            error_log("Erreur lors de la création du parking : " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de la création du parking.";
            header("Location: /?page=create_parking");
            exit;
        }
    }

    /**
     * Supprime un parking
     */
    public function deleteParking(): void 
    {
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id || !is_numeric($id)) {
                $_SESSION['error'] = "ID de parking invalide.";
                header('Location: /?page=admin_parkings');
                exit;
            }
            
            $parkingModel = new Parking();
            if ($parkingModel->deleteParking((int)$id)) {
                $_SESSION['success'] = "Parking supprimé avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression du parking.";
            }
            
            header('Location: /?page=admin_parkings');
            exit;
            
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression du parking : " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de la suppression du parking.";
            header('Location: /?page=admin_parkings');
            exit;
        }
    }

    
    public function add()
{
    $parkingModel = new Parking();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $success = $parkingModel->create([
            'numero_place' => $_POST['numero_place'],
            'etage' => $_POST['etage'],
            'type_place' => $_POST['type_place'],
            'statut' => $_POST['statut'] ?? 'libre',
            'disponible_depuis' => $_POST['disponible_depuis'] ?? date('Y-m-d H:i:s'),
            'actif' => 1,
            'commentaire' => $_POST['commentaire'] ?? null
        ]);

        if ($success) {
            header('Location: /?page=admin_parkings&created=1');
            exit;
        } else {
            $error = "Erreur lors de la création de la place.";
        }
    }

    // Corrigé : on affiche uniquement add_parking.php
    require_once __DIR__ . '/../views/admin/add_parking.php';
}

}




