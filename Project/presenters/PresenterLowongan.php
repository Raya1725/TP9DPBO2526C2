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
        ";
    }
    public function tampilkanLowongan(): string
    {

    }

    public function tampilkanFormLowongan(string|null $id = null): string
    {

    }

    public function tambahLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void
    {

    }

    public function ubahLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void
    {

    }

    public function hapusLowongan(string $id): void
    {

    }
}