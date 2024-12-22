<?php
// Start the session
session_start();

include 'koneksi.php';

// Cek apakah username ada di sesi
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    // Jika username tidak ada, arahkan ke halaman login
    header("Location: login.php");
    exit;
}

// Ambil username dari sesi
$username = $_SESSION['username']; // Mengasumsikan 'username' disimpan di sesi setelah login

// Inisialisasi variabel pencarian dan filter
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Query SQL untuk mengambil data berdasarkan username (melalui user_id)
$query = "SELECT bw.* FROM buku_warung bw
          JOIN users u ON bw.user_id = u.id
          WHERE u.username = '$username'"; // Filter berdasarkan username

// Menambahkan pencarian berdasarkan nama barang
if (!empty($keyword)) {
    $query .= " AND bw.nama_barang LIKE '%$keyword%'";
}

// Menambahkan filter berdasarkan harga atau stok
if (!empty($filter)) {
    if ($filter == 'harga') {
        $query .= " ORDER BY bw.harga";
    } elseif ($filter == 'stok') {
        $query .= " ORDER BY bw.stok";
    }
}

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die('Query error: ' . mysqli_error($koneksi));
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy(); // Hancurkan sesi
    header("Location: login.php"); // Arahkan kembali ke halaman login
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Buku Warung</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        /* Custom styles */
        .help-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .help-button a {
            text-decoration: none;
        }

        .help-button i {
            font-size: 40px;
            color: #25d366;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Buku Warung</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tambah.php">Tambah Catatan</a>
                </li>
                <!-- Menambahkan tombol Logout -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?logout=true">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center">Daftar Catatan Buku Warung</h1>
    <a href="tambah.php" class="btn btn-primary mb-3">Tambah Catatan</a>
    <form method="GET" action="index.php" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="form-control" name="keyword" placeholder="Cari Nama Barang" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="filter">
                    <option value="">Semua</option>
                    <option value="harga" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'harga') ? 'selected' : ''; ?>>Harga</option>
                    <option value="stok" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'stok') ? 'selected' : ''; ?>>Stok</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['nama_barang']."</td>";
                echo "<td>".$row['harga']."</td>";
                echo "<td>".$row['stok']."</td>";
                echo "<td><a href='edit.php?id=".$row['id']."' class='btn btn-warning btn-sm'>Edit</a> | <a href='hapus.php?id=".$row['id']."' class='btn btn-danger btn-sm'>Hapus</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="help-button">
    <a href="https://wa.me/1234567890" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>

<footer class="mt-5">
    <div class="container text-center">
        <p>&copy; <?php echo date("Y"); ?> Buku Warung</p>
    </div>
</footer>

</body>
</html>
