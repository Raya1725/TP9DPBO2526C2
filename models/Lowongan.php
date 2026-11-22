<?php

class Lowongan
{

    private string $id;
    private string $nama;
    private string $asal_perusahaan;
    private int $minimal_skor_tes;

    public function __construct($id, $nama, $asal_perusahaan, $minimal_skor_tes)
    {
        $this->id = $id;
        $this->nama = $nama;
        $this->asal_perusahaan = $asal_perusahaan;
        $this->minimal_skor_tes = $minimal_skor_tes;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama($nama): void
    {
        $this->nama = $nama;
    }

    public function getAsalPerusahaan(): string
    {
        return $this->asal_perusahaan;
    }

    public function setAsalPerusahaan($asal_perusahaan): void
    {
        $this->asal_perusahaan = $asal_perusahaan;
    }

    public function getMinimalSkorTes(): int
    {
        return $this->minimal_skor_tes;
    }

    public function setMinimalSkorTes($minimal_skor_tes): void
    {
        $this->$minimal_skor_tes = $minimal_skor_tes;
    }
}