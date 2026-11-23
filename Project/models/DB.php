<?php
/**
 * Pembungkus koneksi PDO (MySQL): membuka koneksi dan menjalankan query dengan prepared statement.
 * Dijadikan class dasar untuk Tabel* agar logika koneksi tidak diulang di setiap Model (DRY).
 */
class DB
{
    private string $host = "";
    private string $db_name = "";
    private string $username = "";
    private string $password = "";
    // null berarti koneksi belum ada atau sudah ditutup lewat close().
    private ?PDO $conn = null;
    // Menyimpan statement terakhir supaya getAllResult() bisa membaca hasilnya tanpa query diulang.
    private ?PDOStatement $result = null;

    /**
     * Menyimpan parameter koneksi lalu langsung membuka koneksi.
     *
     * @param string $host      Alamat server database.
     * @param string $db_name   Nama database.
     * @param string $username  User database.
     * @param string $password  Password user database.
     *
     * Kompleksitas: waktu O(1) di sisi PHP (waktu jaringan tidak dihitung), memori O(1).
     */
    public function __construct(
        string $host = "",
        string $db_name = "",
        string $username = "",
        string $password = ""
    ) {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;
        $this->conn = $this->connect();
    }

    /**
     * Membuat objek PDO dengan opsi yang aman.
     *
     * @return PDO Koneksi aktif ke database.
     * @throws RuntimeException Bila koneksi gagal.
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function connect(): PDO
    {
        try {
            // utf8mb4 agar karakter Unicode (termasuk emoji) tersimpan dan terbaca dengan benar.
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $options = [
                // Error SQL dilempar sebagai exception supaya kegagalan tidak terjadi diam-diam.
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // Hasil query berupa array dengan nama kolom sebagai kunci, bukan indeks angka.
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // false = memakai prepared statement asli dari MySQL, lebih aman terhadap SQL injection.
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            return new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // Dibungkus RuntimeException agar lapisan atas tidak bergantung pada PDOException.
            // Exception asli disimpan sebagai previous supaya tetap bisa ditelusuri saat debugging.
            throw new RuntimeException("Koneksi Database Gagal: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Menjalankan satu query dengan prepared statement.
     *
     * @param string $query  SQL dengan placeholder (? atau :nama).
     * @param array  $params Nilai untuk placeholder, dikirim terpisah dari teks SQL.
     * @return PDOStatement Statement yang sudah dieksekusi.
     * @throws RuntimeException Bila koneksi sudah ditutup atau query gagal.
     *
     * Kompleksitas: waktu O(1) di sisi PHP; biaya utama ada di database dan tergantung
     * query serta ukuran tabel. Memori O(1) untuk handle statement.
     */
    public function executeQuery(string $query, array $params = []): PDOStatement
    {
        // Guard clause: berhenti lebih awal bila koneksi sudah ditutup (close()).
        if ($this->conn === null) {
            throw new RuntimeException("Tidak ada koneksi database aktif.");
        }
        try {
            // prepare memisahkan struktur SQL dari data. Input pengguna dikirim lewat execute(),
            // sehingga tidak bisa menyisipkan perintah SQL.
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            // Disimpan agar getAllResult() dapat membaca hasil query terakhir.
            $this->result = $stmt;
            return $stmt;
        } catch (PDOException $e) {
            throw new RuntimeException("Query Gagal: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Mengambil semua baris hasil dari query terakhir.
     *
     * @return array Array baris (asosiatif). Kosong bila belum ada query yang dijalankan.
     *
     * Kompleksitas: waktu O(r), memori O(r), r = jumlah baris hasil.
     */
    public function getAllResult(): array
    {
        // Guard clause: belum ada query yang dijalankan, jadi tidak ada hasil untuk dibaca.
        if ($this->result === null) {
            return [];
        }
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Melepas statement dan koneksi. Setelah ini executeQuery() akan menolak dipakai.
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function close(): void
    {
        $this->result = null;
        $this->conn = null;
    }
}