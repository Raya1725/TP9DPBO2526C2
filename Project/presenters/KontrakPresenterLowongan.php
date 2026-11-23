<?php
/**
 * Kontrak Presenter untuk modul Lowongan.
 * Terpisah dari KontrakPresenterKandidat karena tipe parameter berbeda (id Lowongan bertipe string).
 */
interface KontrakPresenterLowongan
{
    /**
     * Menyiapkan data daftar lowongan lalu mengembalikan halaman HTML lengkapnya.
     *
     * @return string HTML halaman daftar.
     */
    public function tampilkanLowongan(): string;
    /**
     * Menampilkan form lowongan: mode ubah bila id diberikan, mode tambah bila null.
     *
     * @param string|null $id
     * @return string HTML halaman form.
     */
    public function tampilkanFormLowongan(?string $id = null): string;
    /**
     * Menyimpan lowongan baru lalu mengarahkan pengguna ke daftar Lowongan.
     */
    public function tambahLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void;
    /**
     * Mengubah data lowongan lalu mengarahkan pengguna ke daftar Lowongan.
     */
    public function ubahLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void;
    /**
     * Menghapus lowongan lalu mengarahkan pengguna ke daftar Lowongan.
     */
    public function hapusLowongan(string $id): void;
}
?>