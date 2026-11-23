# TP9DPBO2526C2
TUGAS PRAKTIKUM 9 DPBO ARSITEKTUR MVP DALAM WEB (PHP & MySQL) Renaldy Heryana (2509867) Ilmu Komputer C2 Universitas Pendidikan Indonesia

# janji
Saya Renaldy Heryana dengan NIM 2509867 mengerjakan TP9 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

# TEMA WEBSITE

Website ini adalah sistem pengelolaan data rekrutmen, yaitu data kandidat karyawan dan data lowongan kerja. Website digunakan untuk mengelola dua data utama, yaitu kandidat (nama, posisi yang dilamar, domisili, skor tes, dan lama pengalaman) dan lowongan (kode lowongan, nama lowongan, perusahaan asal, dan skor tes minimal). Semua data dapat ditambah, dilihat, diubah, dan dihapus (CRUD) langsung dari browser.

Program dibangun dengan arsitektur MVP (Model, View, Presenter) menggunakan PHP berorientasi objek dengan pola Strict Passive View.

# ARSITEKTUR MVP

- Model, adalah class di folder `models` yang berhubungan langsung dengan database. Model hanya menjalankan query dan tidak berisi HTML maupun pengolahan request. Presenter mengenal Model hanya lewat interface (`KontrakModelKandidat` dan `KontrakModelLowongan`)
- View, adalah class di folder `views` yang mengisi template HTML di folder `templateKandidat` dan `templateLowongan` dengan data yang sudah siap pakai. View tidak mengenal Model maupun entity, tidak melakukan perulangan, dan tidak menghitung apa pun (Strict Passive View)
- Presenter, adalah class di folder `presenters` yang menjadi satu-satunya penghubung Model dan View. Presenter mengambil data dari Model, mengubahnya menjadi string atau array biasa (termasuk merakit baris tabel HTML), lalu menyerahkannya ke View
- Front controller, adalah `index.php` yang membaca parameter `page`, `screen`, dan `action`, merakit Model, View, dan Presenter yang sesuai, lalu menyerahkan request ke Presenter

Perbedaan dengan MVC pada TP8: View tidak lagi menerima data langsung dari Model lewat controller, melainkan hanya menerima data jadi dari Presenter.

# DATABASE

Nama database: `mvp_kandidat_db` (MySQL/MariaDB). Akses database memakai PDO.

## TABEL

### Tabel `kandidat`
Menyimpan data kandidat.

- id, adalah primary key berupa angka yang dibuat otomatis oleh database (AUTO_INCREMENT)
- nama, adalah nama kandidat
- posisi, adalah posisi yang dilamar
- domisili, adalah kota domisili kandidat
- skorTes, adalah skor tes kandidat (bawaan 0)
- pengalamanTahun, adalah lama pengalaman kerja dalam tahun (bawaan 0)

### Tabel `lowongan`
Menyimpan data lowongan.

- id, adalah primary key berupa kode lowongan (string) yang diisi manual lewat form
- nama, adalah nama lowongan
- asalPerusahaan, adalah nama perusahaan pembuka lowongan
- minimalSkorTes, adalah skor tes minimal untuk melamar

## RELASI ANTAR TABEL

Tabel `kandidat` dan `lowongan` tidak memiliki foreign key, sehingga keduanya dikelola sendiri-sendiri dan tidak saling membatasi penghapusan.

File SQL hasil export database ada di file `mvp_kandidat_db.sql`.

# STRUKTUR REPO

```
TP9DPBO2526C2
├── Dokumentasi
│   └── Dokumentasi TP9.webm
├── Project
│   ├── models
│   │   ├── DB.php
│   │   ├── Kandidat.php
│   │   ├── KontrakModelKandidat.php
│   │   ├── KontrakModelLowongan.php
│   │   ├── Lowongan.php
│   │   ├── TabelKandidat.php
│   │   └── TabelLowongan.php
│   ├── presenters
│   │   ├── KontrakPresenterKandidat.php
│   │   ├── KontrakPresenterLowongan.php
│   │   ├── PresenterKandidat.php
│   │   └── PresenterLowongan.php
│   ├── templateKandidat
│   │   ├── form.html
│   │   └── skin.html
│   ├── templateLowongan
│   │   ├── form.html
│   │   └── skin.html
│   ├── views
│   │   ├── KontrakView.php
│   │   ├── ViewKandidat.php
│   │   └── ViewLowongan.php
│   ├── config.php
│   └── index.php
├── mvp_kandidat_db.sql
└── README.md
```

# ALUR PROGRAM

1. Program dimulai dari `index.php`. File ini memuat kedua model (`TabelKandidat`, `TabelLowongan`), kedua view, dan kedua presenter, lalu membaca parameter `page` (`?page=kandidat` atau `?page=lowongan`) pada URL.
2. Nilai `page` dicek terhadap daftar yang diizinkan (`ALLOWED_PAGE`). Nilai yang tidak ada atau tidak dikenal diganti nilai bawaan (`kandidat`), sehingga nilai dari URL tidak bisa menentukan class apa yang dibuat.
3. `index.php` merakit objek sesuai `page`. Untuk `lowongan`, dibuat `TabelLowongan`, `ViewLowongan`, lalu `PresenterLowongan`. Selain itu dibuat `TabelKandidat`, `ViewKandidat`, lalu `PresenterKandidat`. Model dan View hanya diberikan ke Presenter, sehingga keduanya tidak pernah saling mengenal. Hanya modul yang dibutuhkan yang dibuat, karena constructor model membuka koneksi database.
4. `index.php` memeriksa metode request. Request `GET` hanya menampilkan halaman, sedangkan request `POST` mengubah data. Dengan begitu data tidak bisa berubah hanya dengan membuka sebuah link.
5. **Read**: pada request `GET` tanpa parameter `screen`, Presenter memanggil model untuk mengambil semua data. Presenter lalu merakit baris tabel HTML dan menghitung total data, kemudian View mengisinya ke template `skin.html`. Kandidat diurutkan dari skor tes tertinggi, lowongan diurutkan dari skor minimal tertinggi. Jika tabel kosong, tampil pesan bahwa data belum ada.
6. **Create**: tombol "+ Tambah" membuka `?screen=add`. Presenter meminta View menampilkan form kosong. Saat form dikirim lewat `POST` dengan `action=tambah`, `index.php` memanggil method tambah milik Presenter, Presenter memanggil model, lalu mengarahkan ke halaman daftar. Id kandidat dibuat otomatis oleh database, sedangkan id lowongan diisi manual lewat form.
7. **Update**: tombol "Edit" membuka `?screen=edit&id=...`. Presenter mengambil data lama dari model dan mengubahnya menjadi array biasa, lalu View mengisinya ke form. Saat form dikirim lewat `POST` dengan `action=ubah`, Presenter memanggil model untuk memperbarui data lalu mengarahkan ke halaman daftar. Pada lowongan, field id disembunyikan saat mode ubah karena id adalah primary key.
8. **Delete**: tombol "Hapus" adalah form `POST` dengan `action=hapus` dan dikonfirmasi lebih dulu lewat `confirm()` di browser. Presenter memanggil model untuk menghapus data lalu mengarahkan ke halaman daftar.
9. Setelah setiap `POST`, program melakukan redirect ke halaman daftar (pola Post/Redirect/Get) agar refresh browser tidak mengirim ulang form.
10. Program berjalan selama server PHP aktif.

# CARA MENJALANKAN

1. Buat database `mvp_kandidat_db` lalu import file `mvp_kandidat_db.sql`. File SQL tidak membuat database sendiri, sehingga database harus dibuat lebih dulu:
   ```bash
   mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS mvp_kandidat_db"
   mysql -u root -p mvp_kandidat_db < mvp_kandidat_db.sql
   ```
   File ini diexport dari MariaDB 11.8. Jika import gagal karena collation `utf8mb4_uca1400_ai_ci` tidak dikenal (versi MySQL/MariaDB yang lebih lama), ganti collation tersebut dengan `utf8mb4_general_ci` pada file SQL.
2. Sesuaikan host, nama database, user, dan password pada `Project/config.php`. Bawaannya `127.0.0.1`, database `mvp_kandidat_db`, user `root`, tanpa password.
3. Jalankan server PHP dari folder `Project`:
   ```bash
   cd Project
   php -S localhost:8000
   ```
   Jika memakai XAMPP atau Laragon, letakkan folder `Project` di `htdocs` atau `www`, lalu buka lewat alamat `http://localhost/Project/index.php`.
4. Buka `http://localhost:8000/index.php?page=kandidat` di browser. Halaman lowongan ada di `http://localhost:8000/index.php?page=lowongan`.

Program memerlukan PHP 8.0 atau lebih baru dengan ekstensi `pdo_mysql`.

# ERROR HANDLING

1. Semua query yang menerima input memakai prepared statement dan `PDO::ATTR_EMULATE_PREPARES` dimatikan, sehingga input user dikirim terpisah dari teks SQL dan tidak bisa menyisipkan SQL.
2. Koneksi PDO memakai mode `ERRMODE_EXCEPTION`. Koneksi atau query yang gagal dilaporkan lewat `RuntimeException` (exception asli disimpan sebagai `previous`), sehingga tidak ada kegagalan yang diam-diam.
3. `executeQuery` menolak dipakai jika koneksi sudah ditutup lewat `close()`.
4. Semua data yang ditampilkan dibungkus `htmlspecialchars` untuk mencegah XSS, baik pada baris tabel (di Presenter) maupun pada isian form (di View).
5. Parameter `page` di URL dicek terhadap daftar yang diizinkan sebelum dipakai.
6. Perubahan data hanya diterima lewat `POST`. Request `GET` tidak pernah mengubah data, dan `action` yang tidak dikenal diarahkan kembali ke halaman daftar tanpa mengubah data.
7. Pada form ubah, Presenter memeriksa lebih dulu bahwa datanya ada (`getKandidatById` atau `getLowonganById`). Jika id tidak ditemukan (misalnya sudah dihapus), pengguna diarahkan ke halaman daftar.
8. Parameter method pada Presenter dan Model memakai deklarasi tipe (`int`, `string`), sehingga nilai dengan tipe yang salah ditolak oleh PHP.
9. Form memakai atribut `required`, `min`, dan `max` (skor tes 0-100) sebagai pemeriksaan di sisi browser.
10. Tabel yang kosong tetap tampil dengan pesan "Belum ada data".

# PENJELASAN ATRIBUT DAN METHOD

## FILE index.php (front controller)

- ALLOWED_ACTIONS, adalah daftar aksi POST yang dikenal, yaitu `tambah`, `ubah`, dan `hapus`
- DEFAULT_PAGE, adalah konstanta halaman bawaan, yaitu `kandidat`
- ALLOWED_PAGE, adalah daftar halaman yang diizinkan, yaitu `kandidat` dan `lowongan`
- LOWONGAN_PAGE, adalah konstanta untuk nilai halaman `lowongan`, dipakai untuk memilih cabang Lowongan

## FILE config.php

- host, adalah alamat server database
- db_name, adalah nama database yang dipakai
- username, adalah nama user database
- password, adalah password user database

## CLASS DB

Mengelola koneksi dan eksekusi query. Class `TabelKandidat` dan `TabelLowongan` mewarisi class ini.

### Atribut
- host, db_name, username, password, adalah atribut private berisi data koneksi
- conn, adalah atribut private berisi objek PDO, bernilai `null` jika koneksi belum ada atau sudah ditutup
- result, adalah atribut private berisi statement dari query terakhir

### Method
- constructor, adalah method untuk menyimpan host, nama database, user, dan password ke atribut lalu langsung membuka koneksi
- connect, adalah method untuk membuat objek PDO dengan charset `utf8mb4`, mode exception, hasil berupa array asosiatif, dan prepared statement asli
- executeQuery, adalah method untuk menjalankan satu query dengan prepared statement dan menyimpan hasilnya
- getAllResult, adalah method untuk mengambil semua baris dari query terakhir, mengembalikan array kosong jika belum ada query
- close, adalah method untuk melepas statement dan koneksi

## CLASS Kandidat (entity)

Merepresentasikan satu baris tabel `kandidat`. Hanya berisi data, getter, dan setter.

- id, adalah atribut private bertipe `?int` karena id dibuat oleh database saat INSERT
- nama, posisi, domisili, adalah atribut private bertipe string
- skorTes, pengalamanTahun, adalah atribut private bertipe int
- constructor, adalah method untuk mengisi semua atribut
- getId, getNama, getPosisi, getDomisili, getSkorTes, getPengalamanTahun, adalah method untuk membaca atribut
- setNama, setPosisi, setDomisili, setSkorTes, setPengalamanTahun, adalah method untuk mengubah atribut

## CLASS Lowongan (entity)

Merepresentasikan satu baris tabel `lowongan`. Id bertipe string karena berupa kode yang diisi manual.

- id, nama, asalPerusahaan, adalah atribut private bertipe string
- minimalSkorTes, adalah atribut private bertipe int
- constructor, adalah method untuk mengisi semua atribut
- getId, getNama, getAsalPerusahaan, getMinimalSkorTes, adalah method untuk membaca atribut
- setId, setNama, setAsalPerusahaan, setMinimalSkorTes, adalah method untuk mengubah atribut

## INTERFACE KontrakModelKandidat dan KontrakModelLowongan

Kontrak yang menjadi satu-satunya hal yang diketahui Presenter tentang Model. Dipisah karena tipe id berbeda (`int` pada kandidat, `string` pada lowongan).

- getAllKandidat / getAllLowongan, adalah method untuk mengambil semua data sebagai array objek entity
- getKandidatById / getLowonganById, adalah method untuk mengambil satu data, mengembalikan `null` jika tidak ditemukan
- addKandidat / addLowongan, adalah method untuk menambah data baru
- updateKandidat / updateLowongan, adalah method untuk mengubah data berdasarkan id
- deleteKandidat / deleteLowongan, adalah method untuk menghapus data berdasarkan id

## CLASS TabelKandidat

Model untuk tabel `kandidat`. Mewarisi `DB` dan mengimplementasikan `KontrakModelKandidat`.

- constructor, adalah method untuk membaca `config.php` lalu membuka koneksi lewat constructor `DB`
- mapRowToKandidat, adalah method private untuk mengubah satu baris hasil query menjadi objek `Kandidat`
- getAllKandidat, adalah method untuk mengambil seluruh kandidat, diurutkan berdasarkan skor tes tertinggi lalu id
- getKandidatById, adalah method untuk mengambil satu kandidat berdasarkan id
- addKandidat, adalah method untuk menambah kandidat baru (nama, posisi, domisili, skorTes, pengalamanTahun)
- updateKandidat, adalah method untuk mengubah seluruh kolom kandidat berdasarkan id
- deleteKandidat, adalah method untuk menghapus kandidat berdasarkan id

## CLASS TabelLowongan

Model untuk tabel `lowongan`. Mewarisi `DB` dan mengimplementasikan `KontrakModelLowongan`.

- constructor, adalah method untuk membaca `config.php` lalu membuka koneksi lewat constructor `DB`
- mapRowToLowongan, adalah method private untuk mengubah satu baris hasil query menjadi objek `Lowongan`
- getAllLowongan, adalah method untuk mengambil seluruh lowongan, diurutkan berdasarkan skor minimal tertinggi
- getLowonganById, adalah method untuk mengambil satu lowongan berdasarkan id
- addLowongan, adalah method untuk menambah lowongan baru (id, nama, asalPerusahaan, minimalSkorTes)
- updateLowongan, adalah method untuk mengubah nama, perusahaan, dan skor minimal berdasarkan id
- deleteLowongan, adalah method untuk menghapus lowongan berdasarkan id

## INTERFACE KontrakView

Kontrak View (Strict Passive View) yang dipakai oleh kedua Presenter.

- renderDaftar, adalah method untuk merender halaman daftar dari HTML baris tabel dan total data yang sudah disiapkan Presenter
- renderForm, adalah method untuk merender halaman form tambah atau ubah dari judul, aksi, dan array data isian awal

## CLASS ViewKandidat dan ViewLowongan

Mengisi template HTML dengan data dari Presenter dengan cara mengganti token seperti `{{TABLE_ROWS}}`.

- skinPath, adalah atribut private berisi lokasi template daftar
- formPath, adalah atribut private berisi lokasi template form
- constructor, adalah method untuk menentukan lokasi template, memakai lokasi bawaan jika argumen kosong
- renderDaftar, adalah method untuk mengisi token `{{TABLE_ROWS}}` dan `{{TOTAL_DATA}}` pada template daftar
- renderForm, adalah method untuk mengisi token form (judul, aksi, dan isian awal) setelah di-escape dengan `htmlspecialchars`

## INTERFACE KontrakPresenterKandidat dan KontrakPresenterLowongan

Kontrak yang menjadi satu-satunya hal yang diketahui `index.php` tentang Presenter.

- tampilkanKandidat / tampilkanLowongan, adalah method untuk mengembalikan HTML halaman daftar
- tampilkanFormKandidat / tampilkanFormLowongan, adalah method untuk mengembalikan HTML form (mode ubah jika id diberikan, mode tambah jika `null`)
- tambahKandidat / tambahLowongan, adalah method untuk menyimpan data baru lalu redirect
- ubahKandidat / ubahLowongan, adalah method untuk mengubah data lalu redirect
- hapusKandidat / hapusLowongan, adalah method untuk menghapus data lalu redirect

## CLASS PresenterKandidat dan PresenterLowongan

Menghubungkan Model dan View.

- model, adalah atribut private berisi objek yang mengimplementasikan kontrak Model
- view, adalah atribut private berisi objek yang mengimplementasikan `KontrakView`
- constructor, adalah method untuk menerima Model dan View lewat interface
- buildRowHtml, adalah method private untuk merakit satu baris `<tr>` tabel, termasuk tombol Edit dan form Hapus, dengan semua nilai di-escape
- tampilkanKandidat / tampilkanLowongan, adalah method untuk mengambil semua data, menghitung total, merakit baris tabel lewat perulangan, lalu meminta View merender daftar
- tampilkanFormKandidat / tampilkanFormLowongan, adalah method untuk menyiapkan form. Pada mode ubah, entity diubah menjadi array biasa dan jika id tidak ditemukan pengguna diarahkan ke daftar
- tambahKandidat / tambahLowongan, ubahKandidat / ubahLowongan, hapusKandidat / hapusLowongan, adalah method untuk memanggil Model lalu redirect ke halaman daftar sesuai modulnya

## FILE TEMPLATE

- templateKandidat/skin.html, adalah template halaman daftar kandidat berisi tabel, total data, tombol tambah, dan tombol menuju halaman Lowongan
- templateKandidat/form.html, adalah template form tambah dan ubah kandidat
- templateLowongan/skin.html, adalah template halaman daftar lowongan berisi tabel, total data, tombol tambah, dan tombol menuju halaman Kandidat
- templateLowongan/form.html, adalah template form tambah dan ubah lowongan, dengan field id yang disembunyikan pada mode ubah

# DOKUMENTASI

Dokumentasi berupa screen record yang mencoba CRUD pada halaman kandidat dan lowongan. Video disimpan di folder `Dokumentasi` dengan nama file `Dokumentasi TP9.webm`.

https://github.com/user-attachments/assets/261a82e9-726e-402b-b7fa-9c2f5e3db192
