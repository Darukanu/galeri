<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = md5($_POST['password']);

// Cek apakah username ada di database
$checkUser = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");

if (mysqli_num_rows($checkUser) == 0) {
    // Jika username tidak ditemukan
    echo "<script>
    alert('Akun tidak ada!');
    location.href='../login.php';
    </script>";
} else {
    // Jika username ditemukan, cek password
    $sql = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($sql);

    if ($cek > 0) {
        $data = mysqli_fetch_array($sql);
        $_SESSION['username'] = $data['username'];
        $_SESSION['userid'] = $data['userid'];
        $_SESSION['status'] = 'login';

        echo "<script>
        location.href='../admin/index.php';
        </script>";
    } else {
        // Jika password salah
        echo "<script>
        alert('username atau password salah!');
        location.href='../login.php';
        </script>";
    }
}
?>
