<?php
/**
 * Konfigurasi koneksi database (host, nama database, user, password).
 * Dipisah dari class Model agar kredensial bisa diganti tanpa mengubah kode lain,
 * dan sebaiknya file ini tidak di-commit ke repository publik karena berisi password.
 */

$host = "127.0.0.1";
$db_name = "mvp_kandidat_db";
$username = "root";
$password = "";