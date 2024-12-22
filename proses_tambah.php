<?php
// Mulai session
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Pastikan username ada di session
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username']; // Ambil username dari session

        // Ambil user_id berdasarkan username dari tabel users
        $query_user = "SELECT id FROM users WHERE username = '$username'";
        $result_user = mysqli_query($koneksi, $query_user);

        if ($result_user && mysqli_num_rows($result_user) > 0) {
            // Jika user ditemukan, ambil user_id
            $row = mysqli_fetch_assoc($result_user);
            $user_id = $row['id'];
        } else {
            // Jika user tidak ditemukan, tampilkan pesan error
            die('User tidak terdaftar atau session telah berakhir');
        }
    } else {
        // Jika session username tidak ada
        die('User tidak terdaftar atau session telah berakhir');
    }

    // Query untuk memasukkan data ke tabel buku_warung
    $query = "INSERT INTO buku_warung (user_id, nama_barang, harga, stok) 
              VALUES ('$user_id', '$nama_barang', '$harga', '$stok')";

    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, redirect ke index.php
        header("Location: index.php");
        exit();
    } else {
        // Menampilkan error jika gagal
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }

    // Menutup koneksi database
    mysqli_close($koneksi);
}
?>
