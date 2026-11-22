<?php
interface KontrakPresenterLowongan
{
    public function tampilkanLowongan(): string;
    public function tampilkanFormLowongan(?string $id = null): string;
    public function tambahLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void;
    public function ubahLowongan(string $id, string $nama, string $asalPerusahaan, int $minimalSkorTes): void;
    public function hapusLowongan(string $id): void;
}
?>