<?php
require_once __DIR__ . '/../../config/constants.php';

class Tarif
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Récupère tous les tarifs indexés par type de véhicule.
     * Exemple de retour :
     * [
     *   'voiture' => ['heure' => 2.5, 'jour' => 15.0],
     *   'moto'    => ['heure' => 1.5, 'jour' => 10.0]
     * ]
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM tarifs");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tarifs = [];

        foreach ($rows as $row) {
            $type = $row['type']; // ex : 'voiture', 'moto', etc.

            $tarifs[$type] = [
                'heure' => isset($row['heure']) ? (float) $row['heure'] : 0.0,
                'jour'  => isset($row['jour'])  ? (float) $row['jour']  : 0.0
            ];
        }

        return $tarifs;
    }

    /**
     * Met à jour un tarif pour un type de véhicule.
     * S'il n'existe pas, il est inséré.
     */
    public function update(string $type, float $heure, float $jour): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tarifs (type, heure, jour)
            VALUES (:type, :heure, :jour)
            ON DUPLICATE KEY UPDATE heure = :heure, jour = :jour
        ");
        return $stmt->execute([
            'type'  => $type,
            'heure' => $heure,
            'jour'  => $jour
        ]);
    }
}
