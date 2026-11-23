<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelKandidat.php';
require_once __DIR__ . '/Kandidat.php';

class TabelKandidat extends DB implements KontrakModelKandidat
{

    public function __construct()
    {
        // require (bukan require_once) agar variabel config terisi di setiap objek baru
        require __DIR__ . '/../config.php';
        parent::__construct($host, $db_name, $username, $password);
    }

    private function mapRowToKandidat(array $row): Kandidat
    {
        return new Kandidat(
            (int) $row['id'],
            $row['nama'],
            $row['posisi'],
            $row['domisili'],
            (int) $row['skorTes'],
            (int) $row['pengalamanTahun']
        );
    }

    /**
     * Mengambil seluruh data kandidat.
     *
     * @return array Daftar kandidat sebagai array asosiatif.
     *
     * Kompleksitas: waktu O(n), memori O(n), n = jumlah baris kandidat.
     */
    public function getAllKandidat(): array
    {
        $query = "SELECT * FROM kandidat ORDER BY skorTes DESC, id ASC";
        $this->executeQuery($query);
        $rows = $this->getAllResult();
        $list = [];
        foreach ($rows as $row) {
            $list[] = $this->mapRowToKandidat($row);
        }
        return $list;
    }

    public function getKandidatById(int $id): ?Kandidat
    {
        $query = "SELECT * FROM kandidat WHERE id = :id LIMIT 1";
        $this->executeQuery($query, [':id' => $id]);
        $rows = $this->getAllResult();
        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToKandidat($rows[0]);
    }

    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void
    {
        $this->executeQuery("INSERT INTO kandidat(nama, posisi, domisili, skorTes, pengalamanTahun) VALUES (?, ?, ?, ?, ?)", [$nama, $posisi, $domisili, $skorTes, $pengalamanTahun]);
    }

    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void
    {
        $this->executeQuery("UPDATE kandidat SET nama = ?, posisi = ?, domisili = ?, skorTes = ?, pengalamanTahun = ? WHERE id = ?", [$nama, $posisi, $domisili, $skorTes, $pengalamanTahun, $id]);
    }

    public function deleteKandidat(int $id): void
    {
        $this->executeQuery("DELETE FROM kandidat WHERE id = ?", [$id]);
    }
}
?>