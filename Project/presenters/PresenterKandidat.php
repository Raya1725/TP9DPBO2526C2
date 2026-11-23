<?php
require_once __DIR__ . '/KontrakPresenterKandidat.php';
require_once __DIR__ . '/../models/KontrakModelKandidat.php';
require_once __DIR__ . '/../views/KontrakView.php';

/**
 * Presenter Kandidat: pusat komunikasi antara View dan Model.
 * Mengambil data dari Model, mengubahnya menjadi string/array biasa, lalu menyerahkannya
 * ke View. Dengan begitu View tidak pernah mengenal Model maupun entity (Strict Passive View).
 */
class PresenterKandidat implements KontrakPresenterKandidat
{
    private KontrakModelKandidat $model;
    private KontrakView $view;

    /**
     * Menerima Model dan View lewat interface (bukan class konkret) agar longgar terikat.
     *
     * @param KontrakModelKandidat $model
     * @param KontrakView $view
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function __construct(KontrakModelKandidat $model, KontrakView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * Helper untuk merakit 1 baris HTML tabel kandidat.
     * Panggil dari dalam tampilkanKandidat() saat melakukan foreach.
     */
    private function buildRowHtml(int $no, Kandidat $kandidat): string
    {
        // Id dipakai di URL dan form; cast (int) memastikan hanya angka yang tertulis.
        $id = (int) $kandidat->getId();
        // HTML baris dirakit di Presenter, jadi escape dilakukan di sini per nilai untuk mencegah XSS
        // dari data yang tersimpan di database.
        $nama = htmlspecialchars($kandidat->getNama());
        $posisi = htmlspecialchars($kandidat->getPosisi());
        $domisili = htmlspecialchars($kandidat->getDomisili());
        // number_format dan satuan hanya untuk tampilan; nilai asli di entity tidak berubah.
        $skorTes = number_format($kandidat->getSkorTes()) . ' / 100';
        $pengalaman = number_format($kandidat->getPengalamanTahun()) . ' Tahun';

        // Hapus memakai form POST, bukan link, karena penghapusan data tidak boleh dipicu oleh GET.
        return "<tr>
            <td class='col-id'>{$no}</td>
            <td><strong>{$nama}</strong></td>
            <td>{$posisi}</td>
            <td>{$domisili}</td>
            <td>{$skorTes}</td>
            <td>{$pengalaman}</td>
            <td class='col-actions'>
                <a href='index.php?screen=edit&id={$id}&page=kandidat' class='btn btn-edit'>Edit</a>
                <form method='post' action='index.php' style='display:inline;' onsubmit=\"return confirm('Hapus kandidat ini?')\">
                    <input type='hidden' name='action' value='hapus'>
                    <input type='hidden' name='id' value='{$id}'>
                    <button type='submit' class='btn btn-delete'>Hapus</button>
                </form>
            </td>
        </tr>";
    }

    /**
     * Menyiapkan baris tabel dan total data, lalu meminta View merender daftar.
     *
     * @return string HTML halaman daftar kandidat.
     *
     * Kompleksitas: waktu O(n), memori O(n), n = jumlah kandidat.
     */
    public function tampilkanKandidat(): string
    {
        $listKandidat = $this->model->getAllKandidat();
        // Total dihitung di Presenter karena Strict Passive View melarang View menghitung atau melakukan loop.
        $totalKandidat = count($listKandidat);
        $rowsHtml = '';
        // Data kosong: tampilkan satu baris pesan agar tabel tidak terlihat rusak.
        if (empty($listKandidat)) {
            $rowsHtml = '<tr><td colspan="7"
style="text-align:center;color:#6b7280;padding:24px;">Belum ada data
kandidat.</td></tr>';
        } else {
            $no = 1;
            // Loop berada di Presenter, bukan di View.
            foreach ($listKandidat as $kandidat) {
                $rowsHtml .= $this->buildRowHtml($no, $kandidat);
                $no++;
            }
        }
        return $this->view->renderDaftar($rowsHtml, $totalKandidat);
    }

    /**
     * Menyiapkan form tambah (id null) atau form ubah (id ada).
     *
     * @param int|null $id Id kandidat yang akan diubah, atau null untuk tambah.
     * @return string HTML halaman form.
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function tampilkanFormKandidat(?int $id = null): string
    {
        // id ada = mode ubah (data lama diisi ke form). id null = mode tambah (form kosong).
        if ($id !== null) {
            $kandidat = $this->model->getKandidatById($id);
            // Id tidak ditemukan (misalnya sudah dihapus): kembali ke daftar, bukan menampilkan form kosong.
            if ($kandidat === null) {
                header("Location: index.php?page=kandidat");
                exit();
            }
            // Entity diubah menjadi array biasa agar View tidak mengenal class Kandidat.
            $formData = [
                'id' => $kandidat->getId(),
                'nama' => $kandidat->getNama(),
                'posisi' => $kandidat->getPosisi(),
                'domisili' => $kandidat->getDomisili(),
                'skorTes' => $kandidat->getSkorTes(),
                'pengalamanTahun' => $kandidat->getPengalamanTahun(),
            ];
            return $this->view->renderForm("Ubah Data Kandidat", "ubah", $formData);
        }
        // Mode tambah: array kosong, sehingga View memakai nilai cadangan.
        return $this->view->renderForm("Tambah Kandidat Baru", "tambah", []);
    }

    /**
     * Menyimpan kandidat baru lewat Model, lalu redirect ke daftar Kandidat.
     *
     * @param string $nama
     * @param string $posisi
     * @param string $domisili
     * @param int    $skorTes
     * @param int    $pengalamanTahun
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function tambahKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void
    {
        $this->model->addKandidat($nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
        // Redirect setelah POST agar refresh tidak mengirim ulang data. exit wajib dipanggil setelah
        // header agar skrip berhenti dan tidak menjalankan kode di bawahnya.
        header('Location: index.php?page=kandidat');
        exit;
    }

    /**
     * Mengubah data kandidat lewat Model, lalu redirect ke daftar Kandidat.
     *
     * @param int    $id
     * @param string $nama
     * @param string $posisi
     * @param string $domisili
     * @param int    $skorTes
     * @param int    $pengalamanTahun
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function ubahKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void
    {
        $this->model->updateKandidat($id, $nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
        header('Location: index.php?page=kandidat');
        exit;
    }

    /**
     * Menghapus kandidat lewat Model, lalu redirect ke daftar Kandidat.
     *
     * @param int $id
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function hapusKandidat(int $id): void
    {
        $this->model->deleteKandidat($id);
        header('Location: index.php?page=kandidat');
        exit;
    }
}