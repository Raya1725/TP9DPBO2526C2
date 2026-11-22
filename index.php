<?php
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';

const ALLOWED_ACTIONS = ['tambah', 'ubah', 'hapus'];

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
            header("Location: index.php");
            exit();
    }

    header("Location: index.php");
    exit();
} else {
    $screen = $_GET['screen'] ?? 'list';
    if ($screen === 'add') {
        echo $presenter->tampilkanFormKandidat();
    } elseif ($screen === 'edit' && isset($_GET['id'])) {
        echo $presenter->tampilkanFormKandidat((int) $_GET['id']);
    } else {
        echo $presenter->tampilkanKandidat();
    }
}
?>