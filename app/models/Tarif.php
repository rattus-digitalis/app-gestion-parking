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

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM tarifs");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tarifs = [
            'voiture' => ['heure' => '', 'jour' => ''],
            'moto'    => ['heure' => '', 'jour' => '']
        ];

        foreach ($rows as $row) {
            $type = $row['type']; // 'voiture' ou 'moto'
            $tarifs[$type] = [
                'heure' => $row['heure'] ?? '',
                'jour'  => $row['jour'] ?? ''
            ];
        }

        return $tarifs;
    }

    public function update(string $type, float $heure, float $jour): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tarifs (type, heure, jour)
            VALUES (:type, :heure, :jour)
            ON DUPLICATE KEY UPDATE heure = :heure, jour = :jour
        ");
        return $stmt->execute([
            'type' => $type,
            'heure' => $heure,
            'jour' => $jour
        ]);
    }
}
