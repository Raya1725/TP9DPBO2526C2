<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelKandidat.php';
require_once __DIR__ . '/Kandidat.php';

/**
 * Model Kandidat: satu-satunya class yang menjalankan SQL untuk tabel kandidat.
 * Mewarisi DB untuk koneksi dan mengimplementasikan KontrakModelKandidat agar bisa
 * dipakai Presenter lewat interface.
 */
class TabelKandidat extends DB implements KontrakModelKandidat
{

    /**
     * Mengambil konfigurasi dari config.php lalu membuka koneksi lewat constructor DB.
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function __construct()
    {
        // require (bukan require_once) agar variabel config terisi di setiap objek baru
        require __DIR__ . '/../config.php';
        parent::__construct($host, $db_name, $username, $password);
    }

    /**
     * Mengubah satu baris hasil query menjadi objek Kandidat.
     * Cast (int) menjaga tipe sesuai constructor Kandidat, karena nilai dari database
     * bisa saja datang sebagai string.
     *
     * @param array $row Satu baris hasil query (kunci = nama kolom).
     * @return Kandidat
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
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
        // Skor tertinggi di atas. id ASC sebagai pembeda agar urutan tetap stabil bila skor sama.
        $query = "SELECT * FROM kandidat ORDER BY skorTes DESC, id ASC";
        $this->executeQuery($query);
        $rows = $this->getAllResult();
        $list = [];
        // Baris array diubah menjadi objek Kandidat supaya Presenter bekerja dengan entity.
        foreach ($rows as $row) {
            $list[] = $this->mapRowToKandidat($row);
        }
        return $list;
    }

    /**
     * Mengambil satu kandidat berdasarkan id.
     *
     * @param int $id
     * @return Kandidat|null null bila id tidak ditemukan.
     *
     * Kompleksitas: waktu O(1) di sisi PHP (pencarian di database memakai primary key),
     * memori O(1).
     */
    public function getKandidatById(int $id): ?Kandidat
    {
        // Named placeholder :id memisahkan data dari teks SQL. LIMIT 1 karena id bersifat unik.
        $query = "SELECT * FROM kandidat WHERE id = :id LIMIT 1";
        $this->executeQuery($query, [':id' => $id]);
        $rows = $this->getAllResult();
        // null berarti tidak ditemukan. Reaksinya diserahkan ke Presenter.
        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToKandidat($rows[0]);
    }

    /**
     * Menyimpan kandidat baru. Kolom id tidak diisi karena dibuat oleh database.
     *
     * @param string $nama
     * @param string $posisi
     * @param string $domisili
     * @param int    $skorTes
     * @param int    $pengalamanTahun
     *
     * Kompleksitas: waktu O(1), memori O(1) (satu prepared statement).
     */
    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void
    {
        $this->executeQuery("INSERT INTO kandidat(nama, posisi, domisili, skorTes, pengalamanTahun) VALUES (?, ?, ?, ?, ?)", [$nama, $posisi, $domisili, $skorTes, $pengalamanTahun]);
    }

    /**
     * Mengubah seluruh kolom kandidat berdasarkan id.
     * Urutan nilai pada array harus sama dengan urutan tanda tanya di SQL, dengan id terakhir
     * karena berada di klausa WHERE.
     *
     * @param int    $id
     * @param string $nama
     * @param string $posisi
     * @param string $domisili
     * @param int    $skorTes
     * @param int    $pengalamanTahun
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void
    {
        $this->executeQuery("UPDATE kandidat SET nama = ?, posisi = ?, domisili = ?, skorTes = ?, pengalamanTahun = ? WHERE id = ?", [$nama, $posisi, $domisili, $skorTes, $pengalamanTahun, $id]);
    }

    /**
     * Menghapus kandidat berdasarkan id.
     *
     * @param int $id
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function deleteKandidat(int $id): void
    {
        $this->executeQuery("DELETE FROM kandidat WHERE id = ?", [$id]);
    }
}
?>