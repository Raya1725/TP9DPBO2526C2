<?php

/**
 * Entity: merepresentasikan satu baris tabel lowongan.
 * Hanya menyimpan data dan menyediakan getter/setter, tanpa logika database atau tampilan.
 */
class Lowongan
{

    // Berbeda dengan Kandidat, id Lowongan bertipe string karena berupa kode yang diisi manual
    // lewat form (misalnya SE001), bukan angka AUTO_INCREMENT.
    private string $id;
    private string $nama;
    private string $asalPerusahaan;
    private int $minimalSkorTes;

    /**
     * Membuat objek Lowongan.
     * Parameter tidak diberi tipe; tipenya dijaga oleh properti bertipe di atas saat penugasan nilai.
     *
     * @param string $id               Kode lowongan.
     * @param string $nama             Nama lowongan.
     * @param string $asalPerusahaan   Nama perusahaan pembuka lowongan.
     * @param int    $minimalSkorTes   Skor tes minimal untuk melamar.
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function __construct($id, $nama, $asalPerusahaan, $minimalSkorTes)
    {
        $this->id = $id;
        $this->nama = $nama;
        $this->asalPerusahaan = $asalPerusahaan;
        $this->minimalSkorTes = $minimalSkorTes;
    }

    /**
     * Mengambil kode lowongan.
     *
     * @return string
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Mengubah kode lowongan.
     *
     * @param mixed $id Nilai baru.
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Mengambil nama lowongan.
     *
     * @return string
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function getNama(): string
    {
        return $this->nama;
    }

    /**
     * Mengubah nama lowongan.
     *
     * @param mixed $nama Nilai baru.
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function setNama($nama): void
    {
        $this->nama = $nama;
    }

    /**
     * Mengambil nama perusahaan asal lowongan.
     *
     * @return string
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function getAsalPerusahaan(): string
    {
        return $this->asalPerusahaan;
    }

    /**
     * Mengubah nama perusahaan asal lowongan.
     *
     * @param mixed $asalPerusahaan Nilai baru.
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function setAsalPerusahaan($asalPerusahaan): void
    {
        $this->asalPerusahaan = $asalPerusahaan;
    }

    /**
     * Mengambil skor tes minimal.
     *
     * @return int
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function getMinimalSkorTes(): int
    {
        return $this->minimalSkorTes;
    }

    /**
     * Mengubah skor tes minimal.
     *
     * @param mixed $minimalSkorTes Nilai baru.
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function setMinimalSkorTes($minimalSkorTes): void
    {
        $this->minimalSkorTes = $minimalSkorTes;
    }
}