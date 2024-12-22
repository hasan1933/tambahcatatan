<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Pastikan path ini benar
require ('koneksi.php');
if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    // Koneksi ke database
    $koneksi = mysqli_connect("localhost", "lmimhx00581tyudv_Users", "bNO)0Y1@k@@)", "lmimhx00581tyudv_portaldb");

    // Cek apakah email ada di database
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        // Email ditemukan, buat token dan kirim email
        $token = bin2hex(random_bytes(50));
        $updateQuery = "UPDATE users SET reset_token='$token' WHERE email='$email'";
        mysqli_query($koneksi, $updateQuery);

        // Kirim email reset password
        $mail = new PHPMailer(true);
        $mail = new PHPMailer(true);
        try {
            // Mengatur PHPMailer untuk menggunakan SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Ganti dengan host SMTP Anda
                $mail->SMTPAuth = true;
                $mail->Username = 'hasanthalib417@gmail.com'; // Email Anda
                $mail->Password = 'kivarobjqmlqiegt'; // Password email Anda
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;


        
            // Penerima dan pengaturan lainnya
            $mail->setFrom('hasanthalib417@gmail.com', 'Reset Sandi');
            $mail->addAddress($email); // Alamat email penerima
            $mail->isHTML(true);
            $mail->isHTML(true);
            $mail->isHTML(true);
$mail->Subject = 'Verifikasi Akun';

// Gunakan HTML untuk tampilan card
$mail->Body = '
<!DOCTYPE html>
<html>
<head>
    <style>
        /* Gaya umum untuk email */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .card {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
        }
        .card-body {
            padding: 20px;
            text-align: center;
        }
        .card-body p {
            font-size: 16px;
            color: #333333;
            margin-bottom: 20px;
        }
        .card-body a {
            display: inline-block;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .card-body a:hover {
            background-color: #0056b3;
        }
        .card-footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            padding: 10px;
            background-color: #f9f9f9;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
             Password Reset Request
        </div>
        <div class="card-body">
            <p>Kami telah menerima permintaan untuk mereset kata sandi Anda. Silakan klik tautan di bawah ini untuk mereset kata sandi Anda::</p>
            <a href="http://bullishfashion.my.id/reset_password.php?token=' . $token . '">Reset Sandi</a>
        </div>
        <div class="card-footer">
            Jika Anda tidak mendaftarkan akun ini, abaikan email ini.
        </div>
    </div>
</body>
</html>';


            $mail->send();
            $email_sent = true;  // Set email_sent ke true jika email berhasil dikirim
            } catch (Exception $e) {
                $email_sent = false;  // Set email_sent ke false jika ada kesalahan
            }
        } else {
            $email_sent = false; // Email tidak ditemukan
        }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h2 class="text-center mb-4">Lupa Password</h2>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" name="forgot_password">Kirim Email Reset Password</button>
                    <p class="text-center mt-3">Sudah punya akun? <a href="login.php">klik disini</a></p>
                </form>
            </div>
        </div>
    </div>

    <?php if (isset($email_sent)): ?>
        <script type="text/javascript">
            // Cek apakah email terkirim
            <?php if ($email_sent): ?>
                alert("Email untuk reset password telah dikirim. Cek inbox atau folder spam Anda.");
            <?php else: ?>
                alert("Terjadi kesalahan. Email tidak ditemukan.");
            <?php endif; ?>
        </script>
    <?php endif; ?>
</body>
</html>
