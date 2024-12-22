<?php
$host = "localhost";
$user = "lmimhx00581tyudv_Users";
$password = "bNO)0Y1@k@@)";
$database = "lmimhx00581tyudv_portaldb";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
