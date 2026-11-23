<?php
/**
 * Kontrak Model untuk data Kandidat.
 * Presenter hanya bergantung pada interface ini, bukan pada class konkret TabelKandidat,
 * sehingga implementasi database bisa diganti (misalnya data palsu untuk pengujian).
 */
interface KontrakModelKandidat
{
    /**
     * Mengambil seluruh data kandidat sebagai array objek Kandidat.
     * @return Kandidat[]
     */
    public function getAllKandidat(): array;

    /**
     * Mengambil satu data kandidat berdasarkan ID.
     */
    public function getKandidatById(int $id): ?Kandidat;

    /**
     * Menyimpan kandidat baru.
     *
     * @param string $nama
     * @param string $posisi
     * @param string $domisili
     * @param int    $skorTes
     * @param int    $pengalamanTahun
     */
    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    /**
     * Mengubah data kandidat yang sudah ada berdasarkan id.
     *
     * @param int    $id  Id kandidat yang diubah.
     * @param string $nama
     * @param string $posisi
     * @param string $domisili
     * @param int    $skorTes
     * @param int    $pengalamanTahun
     */
    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    /**
     * Menghapus kandidat berdasarkan id.
     *
     * @param int $id Id kandidat yang dihapus.
     */
    public function deleteKandidat(int $id): void;
}
?>