<?php

require_once __DIR__ . '/KontrakView.php';

/**
 * View Lowongan (Strict Passive View): hanya mengisi template HTML dengan data dari Presenter.
 * Tidak memuat Model atau entity, tidak melakukan loop, dan tidak menghitung apa pun.
 */
class ViewLowongan implements KontrakView
{
    private string $skinPath;
    private string $formPath;

    /**
     * Menentukan lokasi file template.
     * Operator ?: memakai path default bila argumen kosong; path bisa diganti saat pengujian.
     *
     * @param string $skinPath Path template daftar.
     * @param string $formPath Path template form.
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function __construct(string $skinPath = '', string $formPath = '')
    {
        $this->skinPath = $skinPath ?: __DIR__ . '/../templateLowongan/skin.html';
        $this->formPath = $formPath ?: __DIR__ . '/../templateLowongan/form.html';
    }

    /**
     * Mengisi template daftar dengan baris tabel dan total data.
     *
     * @param string $tableRowsHtml
     * @param int $totalData
     * @return string HTML halaman daftar.
     *
     * Kompleksitas: waktu O(L + S), memori O(L + S), L = panjang template, S = panjang HTML baris.
     */
    public function renderDaftar(string $tableRowsHtml, int $totalData): string
    {
        // Template dibaca sebagai teks biasa. Pengisian data dilakukan lewat penggantian token di bawah.
        $template = file_get_contents($this->skinPath);

        // Pasangan token di template => nilai pengganti.
        // TABLE_ROWS tidak di-escape karena berisi HTML yang sudah disiapkan (dan di-escape) oleh Presenter.
        $tokens = [
            '{{TABLE_ROWS}}' => $tableRowsHtml,
            '{{TOTAL_DATA}}' => (string) $totalData,
        ];
        // Memberi array ke str_replace mengganti semua token sekaligus.
        // Token yang tidak punya pasangan akan tetap tampil apa adanya di halaman.
        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }

    /**
     * Mengisi template form dengan judul, aksi, dan data isian awal.
     *
     * @param string $title
     * @param string $action
     * @param array $formData
     * @return string HTML halaman form.
     *
     * Kompleksitas: waktu O(L), memori O(L), L = panjang template.
     */
    public function renderForm(string $title, string $action, array $formData = []): string
    {
        $template = file_get_contents($this->formPath);

        $tokens = [
            '{{FORM_TITLE}}' => htmlspecialchars($title),
            '{{ACTION}}' => htmlspecialchars($action),
            // htmlspecialchars pada nilai input mencegah HTML rusak (misalnya oleh tanda kutip) dan mencegah XSS.
            // Operator ?? memberi nilai cadangan pada mode tambah ($formData kosong).
            '{{ID}}' => htmlspecialchars($formData['id'] ?? ''),
            '{{NAMA}}' => htmlspecialchars($formData['nama'] ?? ''),
            '{{ASALPERUSAHAAN}}' => htmlspecialchars($formData['asalPerusahaan'] ?? ''),
            // Kurung membuat ?? dijalankan lebih dulu, baru hasilnya diubah ke string untuk htmlspecialchars.
            '{{MINIMALSKORTES}}' => htmlspecialchars((string) ($formData['minimalSkorTes'] ?? 0)),
            '{{IDHIDDEN}}' => htmlspecialchars($formData['idHidden'] ?? ''),
        ];
        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }
}