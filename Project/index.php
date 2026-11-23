<?php
/**
 * Front controller: satu pintu masuk untuk semua request aplikasi.
 * Alurnya: baca parameter page -> buat Model, View, dan Presenter yang sesuai -> serahkan
 * request ke Presenter. index.php tidak berisi logika bisnis ataupun HTML (arsitektur MVP).
 */
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/models/TabelLowongan.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/views/ViewLowongan.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';
require_once __DIR__ . '/presenters/PresenterLowongan.php';

// Daftar aksi POST yang dikenal. Saat ini belum dipakai untuk validasi; aksi yang tidak
// dikenal ditangani oleh case default pada switch di bawah.
const ALLOWED_ACTIONS = ['tambah', 'ubah', 'hapus'];
// Halaman default dipakai saat parameter page tidak ada, sehingga link lama tanpa page
// tetap membuka Kandidat.
const DEFAULT_PAGE = 'kandidat';
// Whitelist nilai page. Nilai dari URL tidak boleh langsung menentukan class apa yang dibuat.
const ALLOWED_PAGE = ['kandidat', 'lowongan'];
const LOWONGAN_PAGE = 'lowongan';

// Operator ?? memberi nilai cadangan bila page tidak ada di URL (tanpa warning).
$page = $_GET['page'] ?? DEFAULT_PAGE;

// Parameter true = perbandingan ketat (tipe ikut dicek). Nilai tak dikenal dikembalikan ke default.
if (!in_array($page, ALLOWED_PAGE, true)) {
    $page = DEFAULT_PAGE;
}

// Cabang Lowongan. Hanya objek modul yang dibutuhkan yang dibuat, karena constructor
// Tabel* membuka koneksi database dan tidak perlu dilakukan dua kali.
if ($page === LOWONGAN_PAGE) {
    // Composition root: Model dan View dibuat di sini, lalu hanya diberikan ke Presenter.
    // Presenter menjadi satu-satunya penghubung keduanya.
    $model = new TabelLowongan();
    $view = new ViewLowongan();
    $presenter = new PresenterLowongan($model, $view);

    // POST = aksi yang mengubah data (tambah/ubah/hapus). GET = hanya menampilkan halaman,
    // jadi perubahan data tidak bisa terjadi hanya dengan membuka sebuah link.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Nilai cadangan '' membuat aksi yang kosong jatuh ke case default, bukan menimbulkan warning.
        $action = $_POST['action'] ?? '';
        $_POST['miminalSkorTes'] = trim($_POST['minimalSkorTes']);
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
            // Aksi tidak dikenal: kembali ke daftar tanpa mengubah data.
            default:
                // aksi tidak dikenal
                header("Location: index.php?page=lowongan");
                exit();
        }

        // Pola Post/Redirect/Get: setelah POST, arahkan ke GET agar refresh tidak mengirim ulang form.
        // Biasanya Presenter sudah redirect dan exit lebih dulu, jadi baris ini berfungsi sebagai cadangan.
        // page=lowongan dibawa agar pengguna kembali ke daftar Lowongan, bukan Kandidat.
        header("Location: index.php?page=lowongan");
        exit();
    } else {
        // Menentukan layar untuk request GET: 'add' = form tambah, 'edit' (wajib ada id) = form ubah,
        // selain itu = daftar.
        $screen = $_GET['screen'] ?? 'list';
        if ($screen === 'add') {
            echo $presenter->tampilkanFormLowongan();
        } elseif ($screen === 'edit' && isset($_GET['id'])) {
            echo $presenter->tampilkanFormLowongan($_GET['id']);
        } else {
            echo $presenter->tampilkanLowongan();
        }
    }
    // Cabang default: Kandidat. Juga dipakai saat nilai page tidak dikenal.
} else {
    // Sama seperti cabang Lowongan: Model dan View dirakit lalu diserahkan ke Presenter.
    $model = new TabelKandidat();
    $view = new ViewKandidat();
    $presenter = new PresenterKandidat($model, $view);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        $_POST['skorTes'] = trim($_POST['skorTes']);
        $_POST['pengalamanTahun'] = trim($_POST['pengalamanTahun']);
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

        // Cadangan redirect. Tanpa page karena halaman default adalah Kandidat.
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