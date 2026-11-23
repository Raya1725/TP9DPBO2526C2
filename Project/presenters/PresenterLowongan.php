<?php
require_once __DIR__ . '/../views/KontrakView.php';
require_once __DIR__ . '/../models/KontrakModelLowongan.php';
require_once __DIR__ . '/KontrakPresenterLowongan.php';

class PresenterLowongan implements KontrakPresenterLowongan
{

    private KontrakModelLowongan $model;
    private KontrakView $view;

    public function __construct(KontrakModelLowongan $model, KontrakView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    private function buildRowHtml(int $no, Lowongan $lowongan): string
    {
        $id = htmlspecialchars($lowongan->getId());
        $nama = htmlspecialchars($lowongan->getNama());
        $asalPerusahaan = htmlspecialchars($lowongan->getAsalPerusahaan());
        $minimalSkorTes = number_format($lowongan->getMinimalSkorTes()) . '/100';

        return "<tr>
            <td class= 'col-id'>{$no}</td>
            <td><strong>{$id}</strong></td>
            <td>{$nama}</td>
            <td>{$asalPerusahaan}</td>
            <td>{$minimalSkorTes}</td>
             <td class='col-actions'>
                <a href='index.php?screen=edit&id={$id}&page=lowongan' class='btn btn-edit'>Edit</a>
                <form method='post' action='index.php?page=lowongan' style='display:inline;' onsubmit=\"return confirm('Hapus kandidat ini?')\">
                    <input type='hidden' name='action' value='hapus'>
                    <input type='hidden' name='id' value='{$id}'>
                    <button type='submit' class='btn btn-delete'>Hapus</button>
                </form>
            </td>
        </tr>";
    }
    public function tampilkanLowongan(): string
    {
        $listLowongan = $this->model->getAllLowongan();
        $totalLowongan = count($listLowongan);
        $rowsHtml = '';
        if (empty($listLowongan)) {
            $rowsHtml = '<tr><td colspan="7"
style="text-align:center;color:#6b7280;padding:24px;">Belum ada data
kandidat.</td></tr>';
        } else {
            $no = 1;
            foreach ($listLowongan as $lowongan) {
                $rowsHtml .= $this->buildRowHtml($no, $lowongan);
                $no++;
            }
        }
        return $this->view->renderDaftar($rowsHtml, $totalLowongan);
    }

    public function tampilkanFormLowongan(string|null $id = null): string
    {
        if ($id !== null) {
            $lowongan = $this->model->getLowonganById($id);
            if ($lowongan === null) {
                header("Location: index.php?page=lowongan");
                exit;
            }
            $formData = [
                'id' => $lowongan->getId(),
                'nama' => $lowongan->getNama(),
                'asalPerusahaan' => $lowongan->getAsalPerusahaan(),
                'minimalSkorTes' => $lowongan->getMinimalSkorTes(),
            ];
            return $this->view->renderForm("Ubah Data Lowongan", "ubah", $formData);
        }
        return $this->view->renderForm("Tambah Lowongan Baru", "tambah", []);
    }

    public function tambahLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void
    {
        $this->model->addLowongan($id, $nama, $asalPerusahaan, $minimalSkorTes);
        header("Location: index.php?page=lowongan");
        exit;
    }

    public function ubahLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void
    {
        $this->model->updateLowongan($id, $nama, $asalPerusahaan, $minimalSkorTes);
        header("Location: index.php?page=lowongan");
        exit;
    }

    public function hapusLowongan(string $id): void
    {
        $this->model->deleteLowongan($id);
        header("Location: index.php?page=lowongan");
        exit;
    }
}