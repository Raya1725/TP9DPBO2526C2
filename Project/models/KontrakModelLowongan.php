<?php
/**
 * Kontrak Model untuk data Lowongan.
 * Dibuat terpisah dari KontrakModelKandidat karena parameter tiap method berbeda
 * (misalnya id bertipe string pada Lowongan).
 */
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

    /**
     * Menyimpan lowongan baru. Id diisi dari luar (bukan dibuat database).
     *
     * @param string $id
     * @param string $nama
     * @param string $asalPerusahaan
     * @param int    $minimalSkorTes
     */
    public function addLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void;
    /**
     * Mengubah data lowongan berdasarkan id.
     *
     * @param string $id  Id lowongan yang diubah.
     * @param string $nama
     * @param string $asalPerusahaan
     * @param int    $minimalSkorTes
     */
    public function updateLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void;
    /**
     * Menghapus lowongan berdasarkan id.
     *
     * @param string $id Id lowongan yang dihapus.
     */
    public function deleteLowongan(string $id): void;
}
?>