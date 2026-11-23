<?php
require_once __DIR__ . '/../views/KontrakView.php';
require_once __DIR__ . '/../models/KontrakModelLowongan.php';
require_once __DIR__ . '/KontrakPresenterLowongan.php';

/**
 * Presenter Lowongan: pusat komunikasi antara View dan Model untuk modul Lowongan.
 * Seluruh data tampilan disiapkan di sini, sehingga View tidak mengenal Model maupun entity.
 */
class PresenterLowongan implements KontrakPresenterLowongan
{

    private KontrakModelLowongan $model;
    private KontrakView $view;

    /**
     * Menerima Model dan View lewat interface (bukan class konkret).
     *
     * @param KontrakModelLowongan $model
     * @param KontrakView $view
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function __construct(KontrakModelLowongan $model, KontrakView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * Merakit satu baris HTML tabel lowongan.
     *
     * @param int      $no       Nomor urut baris.
     * @param Lowongan $lowongan Entity yang ditampilkan.
     * @return string HTML satu baris <tr>.
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    private function buildRowHtml(int $no, Lowongan $lowongan): string
    {
        // Semua nilai di-escape di sini (bukan di View) karena HTML baris dirakit oleh Presenter.
        // Id di-escape juga karena berupa string bebas yang diisi pengguna.
        $id = htmlspecialchars($lowongan->getId());
        $nama = htmlspecialchars($lowongan->getNama());
        $asalPerusahaan = htmlspecialchars($lowongan->getAsalPerusahaan());
        $minimalSkorTes = number_format($lowongan->getMinimalSkorTes()) . '/100';

        // Edit dan Hapus membawa page=lowongan agar request masuk ke cabang Lowongan di index.php.
        // Hapus memakai form POST, bukan link, karena penghapusan data tidak boleh dipicu oleh GET.
        return "<tr>
            <td class= 'col-id'>{$no}</td>
            <td><strong>{$id}</strong></td>
            <td>{$nama}</td>
            <td>{$asalPerusahaan}</td>
            <td>{$minimalSkorTes}</td>
             <td class='col-actions'>
                <a href='index.php?screen=edit&id={$id}&page=lowongan' class='btn btn-edit'>Edit</a>
                <form method='post' action='index.php?page=lowongan' style='display:inline;' onsubmit=\"return confirm('Hapus lowongan ini?')\">
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
     * @return string HTML halaman daftar lowongan.
     *
     * Kompleksitas: waktu O(n), memori O(n), n = jumlah lowongan.
     */
    public function tampilkanLowongan(): string
    {
        $listLowongan = $this->model->getAllLowongan();
        // Total dihitung di Presenter karena Strict Passive View melarang View menghitung atau melakukan loop.
        $totalLowongan = count($listLowongan);
        $rowsHtml = '';
        // Data kosong: tampilkan satu baris pesan agar tabel tidak terlihat rusak.
        if (empty($listLowongan)) {
            $rowsHtml = '<tr><td colspan="7"
style="text-align:center;color:#6b7280;padding:24px;">Belum ada data
lowongan.</td></tr>';
        } else {
            $no = 1;
            // Loop berada di Presenter, bukan di View.
            foreach ($listLowongan as $lowongan) {
                $rowsHtml .= $this->buildRowHtml($no, $lowongan);
                $no++;
            }
        }
        return $this->view->renderDaftar($rowsHtml, $totalLowongan);
    }

    /**
     * Menyiapkan form tambah (id null) atau form ubah (id ada).
     *
     * @param string|null $id Id lowongan yang akan diubah, atau null untuk tambah.
     * @return string HTML halaman form.
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function tampilkanFormLowongan(string|null $id = null): string
    {
        // id ada = mode ubah (data lama diisi ke form). id null = mode tambah (form kosong).
        if ($id !== null) {
            $lowongan = $this->model->getLowonganById($id);
            // Id tidak ditemukan (misalnya sudah dihapus): kembali ke daftar Lowongan.
            if ($lowongan === null) {
                header("Location: index.php?page=lowongan");
                exit;
            }
            // Entity diubah menjadi array biasa agar View tidak mengenal class Lowongan.
            $formData = [
                'id' => $lowongan->getId(),
                'nama' => $lowongan->getNama(),
                'asalPerusahaan' => $lowongan->getAsalPerusahaan(),
                'minimalSkorTes' => $lowongan->getMinimalSkorTes(),
                'idHidden' => 'hidden',
            ];
            return $this->view->renderForm("Ubah Data Lowongan", "ubah", $formData);
        }
        // Mode tambah: array kosong, sehingga View memakai nilai cadangan.
        return $this->view->renderForm("Tambah Lowongan Baru", "tambah", []);
    }

    /**
     * Menyimpan lowongan baru lewat Model, lalu redirect ke daftar Lowongan.
     *
     * @param string $id
     * @param string $nama
     * @param string $asalPerusahaan
     * @param int    $minimalSkorTes
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function tambahLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void
    {
        $this->model->addLowongan($id, $nama, $asalPerusahaan, $minimalSkorTes);
        // Redirect setelah POST agar refresh tidak mengirim ulang data. page=lowongan menjaga pengguna
        // tetap di modul Lowongan. exit wajib agar skrip berhenti setelah header.
        header("Location: index.php?page=lowongan");
        exit;
    }

    /**
     * Mengubah data lowongan lewat Model, lalu redirect ke daftar Lowongan.
     *
     * @param string $id
     * @param string $nama
     * @param string $asalPerusahaan
     * @param int    $minimalSkorTes
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function ubahLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void
    {
        $this->model->updateLowongan($id, $nama, $asalPerusahaan, $minimalSkorTes);
        header("Location: index.php?page=lowongan");
        exit;
    }

    /**
     * Menghapus lowongan lewat Model, lalu redirect ke daftar Lowongan.
     *
     * @param string $id
     *
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function hapusLowongan(string $id): void
    {
        $this->model->deleteLowongan($id);
        header("Location: index.php?page=lowongan");
        exit;
    }
}