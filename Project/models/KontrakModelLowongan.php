<?php
interface KontrakModelLowongan
{
    /**
     * Mengambil seluruh data lowongan sebagai array objek lowongan.
     * @return Lowongan[]
     */
    public function getAllLowongan(): array;

    /**
     * Mengambil satu data Lowongan berdasarkan ID.
     */
    public function getLowonganById(string $id): ?Lowongan;

    public function addLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void;
    public function updateLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void;
    public function deleteLowongan(string $id): void;
}
?>