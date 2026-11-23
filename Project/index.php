<?php
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/models/TabelLowongan.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/views/ViewLowongan.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';
require_once __DIR__ . '/presenters/PresenterLowongan.php';

const ALLOWED_ACTIONS = ['tambah', 'ubah', 'hapus'];
const DEFAULT_PAGE = 'kandidat';
const ALLOWED_PAGE = ['kandidat', 'lowongan'];
const LOWONGAN_PAGE = 'lowongan';

$page = $_GET['page'] ?? DEFAULT_PAGE;

if (!in_array($page, ALLOWED_PAGE, true)) {
    $page = DEFAULT_PAGE;
}

if ($page === LOWONGAN_PAGE) {
    $model = new TabelLowongan();
    $view = new ViewLowongan();
    $presenter = new PresenterLowongan($model, $view);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // TODO: Tangani $_POST['action'] untuk 'tambah', 'ubah', dan 'hapus'
        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'tambah':
                $presenter->tambahLowongan($_POST['id'], $_POST['nama'], $_POST['asalPerusahaan'], $_POST['minimalSkorTes']);
                break;
            case 'ubah':
                // panggil presenter
                $presenter->ubahLowongan($_POST['id'], $_POST['nama'], $_POST['asalPerusahaan'], $_POST['minimalSkorTes']);
                break;
            case 'hapus':
                // panggil presenter
                $presenter->hapusLowongan($_POST['id']);
                break;
            default:
                // aksi tidak dikenal
                header("Location: index.php?page=lowongan");
                exit();
        }

        header("Location: index.php?page=lowongan");
        exit();
    } else {
        $screen = $_GET['screen'] ?? 'list';
        if ($screen === 'add') {
            echo $presenter->tampilkanFormLowongan();
        } elseif ($screen === 'edit' && isset($_GET['id'])) {
            echo $presenter->tampilkanFormLowongan($_GET['id']);
        } else {
            echo $presenter->tampilkanLowongan();
        }
    }
} else {
    $model = new TabelKandidat();
    $view = new ViewKandidat();
    $presenter = new PresenterKandidat($model, $view);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // TODO: Tangani $_POST['action'] untuk 'tambah', 'ubah', dan 'hapus'
        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'tambah':
                $presenter->tambahKandidat($_POST['nama'], $_POST['posisi'], $_POST['domisili'], $_POST['skorTes'], $_POST['pengalamanTahun']);
                break;
            case 'ubah':
                // panggil presenter
                $presenter->ubahKandidat($_POST['id'], $_POST['nama'], $_POST['posisi'], $_POST['domisili'], $_POST['skorTes'], $_POST['pengalamanTahun']);
                break;
            case 'hapus':
                // panggil presenter
                $presenter->hapusKandidat($_POST['id']);
                break;
            default:
                // aksi tidak dikenal
                header("Location: index.php?page=kandidat");
                exit();
        }

        header("Location: index.php");
        exit();
    } else {
        $screen = $_GET['screen'] ?? 'list';
        if ($screen === 'add') {
            echo $presenter->tampilkanFormKandidat();
        } elseif ($screen === 'edit' && isset($_GET['id'])) {
            echo $presenter->tampilkanFormKandidat($_GET['id']);
        } else {
            echo $presenter->tampilkanKandidat();
        }
    }
}