<?php
/**
 * Entity: merepresentasikan satu baris tabel kandidat.
 * Hanya menyimpan data dan menyediakan getter/setter, tanpa logika database atau tampilan
 * (Single Responsibility).
 */
class Kandidat
{
    // Nullable karena id diberikan oleh database saat INSERT (kolom id tidak diisi dari aplikasi).
    private ?int $id;
    private string $nama;
    private string $posisi;
    private string $domisili;
    private int $skorTes;
    private int $pengalamanTahun;

    /**
     * Membuat objek Kandidat dari nilai yang sudah bertipe benar.
     *
     * @param int|null $id               ID kandidat.
     * @param string   $nama             Nama lengkap.
     * @param string   $posisi           Posisi yang dilamar.
     * @param string   $domisili         Kota domisili.
     * @param int      $skorTes          Skor tes (0-100).
     * @param int      $pengalamanTahun  Lama pengalaman kerja dalam tahun.
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function __construct(
        ?int $id,
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ) {
        $this->id = $id;
        $this->nama = $nama;
        $this->posisi = $posisi;
        $this->domisili = $domisili;
        $this->skorTes = $skorTes;
        $this->pengalamanTahun = $pengalamanTahun;
    }

    /**
     * Mengambil ID kandidat.
     *
     * @return int|null
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * Mengambil nama kandidat.
     *
     * @return string
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function getNama(): string
    {
        return $this->nama;
    }
    /**
     * Mengambil posisi yang dilamar.
     *
     * @return string
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function getPosisi(): string
    {
        return $this->posisi;
    }
    /**
     * Mengambil domisili kandidat.
     *
     * @return string
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function getDomisili(): string
    {
        return $this->domisili;
    }
    /**
     * Mengambil skor tes kandidat.
     *
     * @return int
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function getSkorTes(): int
    {
        return $this->skorTes;
    }
    /**
     * Mengambil lama pengalaman kerja (tahun).
     *
     * @return int
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function getPengalamanTahun(): int
    {
        return $this->pengalamanTahun;
    }

    /**
     * Mengubah nama kandidat.
     *
     * @param string $nama
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function setNama(string $nama): void
    {
        $this->nama = $nama;
    }
    /**
     * Mengubah posisi yang dilamar.
     *
     * @param string $posisi
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function setPosisi(string $posisi): void
    {
        $this->posisi = $posisi;
    }
    /**
     * Mengubah domisili kandidat.
     *
     * @param string $domisili
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function setDomisili(string $domisili): void
    {
        $this->domisili = $domisili;
    }
    /**
     * Mengubah skor tes kandidat.
     *
     * @param int $skorTes
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function setSkorTes(int $skorTes): void
    {
        $this->skorTes = $skorTes;
    }
    /**
     * Mengubah lama pengalaman kerja (tahun).
     *
     * @param int $pengalamanTahun
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function setPengalamanTahun(int $pengalamanTahun): void
    {
        $this->pengalamanTahun = $pengalamanTahun;
    }
}
?>