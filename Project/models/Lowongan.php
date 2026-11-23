<?php

class Lowongan
{

    private string $id;
    private string $nama;
    private string $asalPerusahaan;
    private int $minimalSkorTes;

    public function __construct($id, $nama, $asalPerusahaan, $minimalSkorTes)
    {
        $this->id = $id;
        $this->nama = $nama;
        $this->asalPerusahaan = $asalPerusahaan;
        $this->minimalSkorTes = $minimalSkorTes;
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
        return $this->asalPerusahaan;
    }

    public function setAsalPerusahaan($asalPerusahaan): void
    {
        $this->asalPerusahaan = $asalPerusahaan;
    }

    public function getMinimalSkorTes(): int
    {
        return $this->minimalSkorTes;
    }

    public function setMinimalSkorTes($minimalSkorTes): void
    {
        $this->$minimalSkorTes = $minimalSkorTes;
    }
}