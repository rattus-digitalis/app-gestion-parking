<?php
require_once __DIR__ . '/../models/Tarif.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class AdminTarifController
{
    public function show()
    {
        $tarifModel = new Tarif();
        $tarifs = $tarifModel->getAll();

        // Si aucun tarif n’est trouvé, initialiser à vide
        if (!$tarifs || !is_array($tarifs)) {
            $tarifs = [
                'voiture' => ['heure' => '', 'jour' => ''],
                'moto'    => ['heure' => '', 'jour' => '']
            ];
        }

        require __DIR__ . '/../views/admin/tarifs.php';
    }

    public function update(array $data)
    {
        if (!isset($data['tarifs']) || !is_array($data['tarifs'])) {
            http_response_code(400);
            echo "Erreur : Données invalides.";
            exit;
        }

        $tarifModel = new Tarif();

        foreach ($data['tarifs'] as $type => $valeurs) {
            $heure = isset($valeurs['heure']) ? (float) $valeurs['heure'] : 0.0;
            $jour  = isset($valeurs['jour'])  ? (float) $valeurs['jour']  : 0.0;

            // protection contre types inattendus
            if (in_array($type, ['voiture', 'moto'])) {
                $tarifModel->update($type, $heure, $jour);
            }
        }

        header("Location: /?page=admin_tarifs");
        exit;
    }
}
