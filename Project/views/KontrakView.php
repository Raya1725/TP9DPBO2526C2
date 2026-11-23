<?php
/**
 * Kontrak View (Strict Passive View)
 * View tidak mengenal entitas domain, tidak melakukan loop, dan tidak menghitung agregat.
 */
interface KontrakView {
    /**
     * Merender halaman daftar dari data yang sudah siap pakai.
     *
     * @param string $tableRowsHtml HTML baris tabel yang dirakit Presenter.
     * @param int    $totalData     Total data yang dihitung Presenter.
     * @return string HTML halaman lengkap.
     */
    public function renderDaftar(string $tableRowsHtml, int $totalData): string;
    /**
     * Merender halaman form tambah atau ubah.
     *
     * @param string $title    Judul form.
     * @param string $action   Nilai aksi form (tambah atau ubah).
     * @param array  $formData Data isian awal; kosong pada mode tambah.
     * @return string HTML halaman lengkap.
     */
    public function renderForm(string $title, string $action, array $formData = []): string;
}
?>
