<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelLowongan.php';
require_once __DIR__ . '/Lowongan.php';

class TabelLowongan extends DB implements KontrakModelLowongan
{

    public function __construct()
    {
        require "/home/raya1725/Project/College/Semester3/DPBO/Prakday9/TP9DPBO2526C2" . '/config.php';
        parent::__construct($host, $db_name, $username, $password);
    }

    private function mapRowToLowongan(array $row): Lowongan
    {
        return new Lowongan(
            (int) $row['id'],
            $row['nama'],
            $row['asal_perusahaan'],
            (int) $row['minimalSkorTes']
        );
    }

    public function getAllLowongan(): array
    {
        $query = "SELECT * FROM lowongan ORDER BY skorTes DESC, id ASC";
        $this->executeQuery($query);
        $rows = $this->getAllResult();
        $list = [];
        foreach ($rows as $row) {
            $list[] = $this->mapRowToLowongan($row);
        }
        return $list;
    }

    public function getLowonganById(string $id): ?Lowongan
    {
        $query = "SELECT * FROM lowongan where id = :id LIMIT 1";
        $this->executeQuery($query, [':id' => $id]);
        $rows = $this->getAllResult();
        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToLowongan($rows[0]);
    }


    public function addLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void
    {
        $this->executeQuery(
            "INSERT INTO lowongan (id, nama, asalPerusahaan, minimalSkorTes) VALUES (?, ?, ?, ?)",
            [$id, $nama, $asalPerusahaan, $minimalSkorTes]
        );
    }

    public function deleteLowongan(string $id): void
    {
        $this->executeQuery("DELETE FROM lowongan WHERE id = ?", [$id]);
    }

    public function updateLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void
    {
        $this->executeQuery("UPDATE lowongan SET nama = ?, asalPerusahaan = ?, minimalSkorTes = ? WHERE id = ?", [$nama, $asalPerusahaan, $minimalSkorTes, $id]);
    }


}