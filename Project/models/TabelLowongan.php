<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelLowongan.php';
require_once __DIR__ . '/Lowongan.php';
/**
 * Model Lowongan: satu-satunya class yang menjalankan SQL untuk tabel lowongan.
 * Strukturnya sengaja sejajar dengan TabelKandidat agar mudah dipahami dan dirawat.
 */
class TabelLowongan extends DB implements KontrakModelLowongan
{

    /**
     * Mengambil konfigurasi dari config.php lalu membuka koneksi lewat constructor DB.
     * Memakai require (bukan require_once) agar variabel konfigurasi terisi di setiap objek baru.
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function __construct()
    {
        require __DIR__ . '/../config.php';
        parent::__construct($host, $db_name, $username, $password);
    }

    /**
     * Mengubah satu baris hasil query menjadi objek Lowongan.
     * Nama kunci harus sama persis dengan nama kolom di tabel lowongan.
     *
     * @param array $row Satu baris hasil query (kunci = nama kolom).
     * @return Lowongan
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    private function mapRowToLowongan(array $row): Lowongan
    {
        return new Lowongan(
            $row['id'],
            $row['nama'],
            $row['asalPerusahaan'],
            (int) $row['minimalSkorTes']
        );
    }

    /**
     * Mengambil seluruh data lowongan.
     *
     * @return Lowongan[] Daftar objek Lowongan.
     *
     * Kompleksitas: waktu O(n), memori O(n), n = jumlah baris lowongan.
     */
    public function getAllLowongan(): array
    {
        // Lowongan dengan skor minimal tertinggi ditampilkan paling atas.
        $query = "SELECT * FROM lowongan ORDER BY minimalSkorTes DESC";
        $this->executeQuery($query);
        $rows = $this->getAllResult();
        $list = [];
        // Baris array diubah menjadi objek Lowongan supaya Presenter bekerja dengan entity.
        foreach ($rows as $row) {
            $list[] = $this->mapRowToLowongan($row);
        }
        return $list;
    }

    /**
     * Mengambil satu lowongan berdasarkan id.
     *
     * @param string $id
     * @return Lowongan|null null bila id tidak ditemukan.
     *
     * Kompleksitas: waktu O(1) di sisi PHP, memori O(1).
     */
    public function getLowonganById(string $id): ?Lowongan
    {
        // Named placeholder :id memisahkan data dari teks SQL. LIMIT 1 karena id bersifat unik.
        $query = "SELECT * FROM lowongan where id = :id LIMIT 1";
        $this->executeQuery($query, [':id' => $id]);
        $rows = $this->getAllResult();
        // null berarti tidak ditemukan. Reaksinya diserahkan ke Presenter.
        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToLowongan($rows[0]);
    }


    /**
     * Menyimpan lowongan baru. Id disertakan karena berupa kode yang diisi dari form.
     *
     * @param string $id
     * @param string $nama
     * @param string $asalPerusahaan
     * @param int    $minimalSkorTes
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function addLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void
    {
        $this->executeQuery(
            "INSERT INTO lowongan (id, nama, asalPerusahaan, minimalSkorTes) VALUES (?, ?, ?, ?)",
            [$id, $nama, $asalPerusahaan, $minimalSkorTes]
        );
    }

    /**
     * Menghapus lowongan berdasarkan id.
     *
     * @param string $id
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function deleteLowongan(string $id): void
    {
        $this->executeQuery("DELETE FROM lowongan WHERE id = ?", [$id]);
    }

    /**
     * Mengubah nama, perusahaan, dan skor minimal lowongan berdasarkan id.
     * Id tidak ikut diubah; ia hanya dipakai di klausa WHERE (karena itu berada di urutan terakhir).
     *
     * @param string $id
     * @param string $nama
     * @param string $asalPerusahaan
     * @param int    $minimalSkorTes
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function updateLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void
    {
        $this->executeQuery("UPDATE lowongan SET nama = ?, asalPerusahaan = ?, minimalSkorTes = ? WHERE id = ?", [$nama, $asalPerusahaan, $minimalSkorTes, $id]);
    }


}