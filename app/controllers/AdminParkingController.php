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
            // Vérifier l'unicité
if ($parkingModel->placeExists($numeroPlace)) {
    $errors[] = "Ce numéro de place existe déjà.";
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
    // ✅ Définir la page actuelle pour le template
    $current_page = 'add_parking';
    
    $parkingModel = new Parking();
    $errors = [];
    $success = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Debug pour voir ce qui arrive
        error_log("POST reçu dans add(): " . print_r($_POST, true));
        
        // Validation des données pour une PLACE de parking
        if (empty($_POST['numero_place'])) {
            $errors[] = "Le numéro de place est obligatoire.";
        }
        
        if (empty($_POST['type_place'])) {
            $errors[] = "Le type de place est obligatoire.";
        }
        
        // Validation du type de place (selon votre BDD)
        $typesValides = ['standard', 'handicap', 'electrique', 'moto'];
        if (!empty($_POST['type_place']) && !in_array($_POST['type_place'], $typesValides)) {
            $errors[] = "Type de place invalide.";
        }
        
        // Validation du statut (selon votre BDD)
        $statutsValides = ['libre', 'occupe', 'reserve', 'maintenance'];
        $statut = $_POST['statut'] ?? 'libre';
        if (!in_array($statut, $statutsValides)) {
            $errors[] = "Statut invalide.";
        }
        
        // Validation de l'étage (doit être un entier)
        $etage = $_POST['etage'] ?? 0;
        if (!is_numeric($etage)) {
            $errors[] = "L'étage doit être un nombre.";
        }
        
        // Validation du numéro de place (unicité et format)
        if (!empty($_POST['numero_place'])) {
            $numeroPlace = trim(strtoupper($_POST['numero_place']));
            if (strlen($numeroPlace) > 10) {
                $errors[] = "Le numéro de place ne peut pas dépasser 10 caractères.";
            }
        
        }
        
        // Si aucune erreur, créer la place
        if (empty($errors)) {
            $placeData = [
                'numero_place' => trim(strtoupper($_POST['numero_place'])),
                'etage' => (int)($_POST['etage'] ?? 0),
                'type_place' => $_POST['type_place'],
                'statut' => $statut,
                'disponible_depuis' => $_POST['disponible_depuis'] ?? date('Y-m-d H:i:s'),
                'actif' => isset($_POST['actif']) ? 1 : 0,
                'commentaire' => trim($_POST['commentaire'] ?? ''),
                'derniere_reservation_id' => null,
                'date_maj' => date('Y-m-d H:i:s')
            ];
            
            try {
                // Créer la place (utilise la méthode create du modèle)
                $result = $parkingModel->create($placeData);
                
                if ($result) {
                    $success = "Place de parking créée avec succès !";
                    // Optionnel : rediriger vers la liste après 2 secondes
                    // header('refresh:2;url=/?page=admin_parkings');
                } else {
                    $errors[] = "Erreur lors de la création de la place.";
                }
            } catch (Exception $e) {
                error_log("Erreur création place: " . $e->getMessage());
                $errors[] = "Erreur lors de la création de la place: " . $e->getMessage();
            }
        }
    }

    // Afficher le formulaire avec les erreurs/succès
    require_once __DIR__ . '/../views/admin/add_parking.php';
}
}




