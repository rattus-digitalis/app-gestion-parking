<?php
require_once __DIR__ . '/../models/Tarif.php';

class AdminTarifController
{
    public function show()
    {
        $tarifModel = new Tarif();
        $tarifs = $tarifModel->getAll();

        // Valeurs par défaut si rien n’est trouvé en BDD
        if (!$tarifs) {
            $tarifs = [
                'voiture' => ['heure' => '', 'jour' => ''],
                'moto'    => ['heure' => '', 'jour' => '']
            ];
        }

        require __DIR__ . '/../views/admin/tarifs.php';
    }

    public function update(array $data)
    {
        $tarifModel = new Tarif();

        foreach ($data['tarifs'] as $type => $valeurs) {
            $heure = isset($valeurs['heure']) ? (float)$valeurs['heure'] : 0.0;
            $jour  = isset($valeurs['jour'])  ? (float)$valeurs['jour']  : 0.0;
            $tarifModel->update($type, $heure, $jour);
        }

        header("Location: /?page=admin_tarifs");
        exit;
    }
}
