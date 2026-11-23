<?php
/**
 * Kontrak Presenter untuk modul Kandidat.
 * index.php hanya mengenal interface ini, sehingga cara kerja di dalam Presenter bisa berubah
 * tanpa mengubah index.php.
 */
interface KontrakPresenterKandidat
{
    /**
     * Menyiapkan data daftar kandidat lalu mengembalikan halaman HTML lengkapnya.
     *
     * @return string HTML halaman daftar.
     */
    public function tampilkanKandidat(): string;
    /**
     * Menampilkan form kandidat: mode ubah bila id diberikan, mode tambah bila null.
     *
     * @param int|null $id
     * @return string HTML halaman form.
     */
    public function tampilkanFormKandidat(?int $id = null): string;
    /**
     * Menyimpan kandidat baru lalu mengarahkan pengguna ke daftar.
     */
    public function tambahKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    /**
     * Mengubah data kandidat lalu mengarahkan pengguna ke daftar.
     */
    public function ubahKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    /**
     * Menghapus kandidat lalu mengarahkan pengguna ke daftar.
     */
    public function hapusKandidat(int $id): void;
}
?>