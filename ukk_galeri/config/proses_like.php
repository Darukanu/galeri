<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['userid'])) {
    echo "<script>
    alert('Anda belum login!');
    location.href='../login.php';
    </script>";
    exit;
}

$fotoid = $_GET['fotoid'];
$userid = $_SESSION['userid'];
$albumid = isset($_GET['albumid']) ? $_GET['albumid'] : '';



// Cek apakah pengguna sudah menyukai foto
$cekSuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");

if (mysqli_num_rows($cekSuka) == 1) {
    // Jika sudah suka, batalkan suka
    $query = mysqli_query($koneksi, "DELETE FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
    if ($query) {
        echo "<script>
        location.href='../admin/home.php?albumid=$albumid';
        </script>";
    } else {
        echo "<script>
        alert('Gagal membatalkan suka.');
        location.href='../admin/home.php?albumid=$albumid';
        </script>";
    }
} else {
    // Jika belum suka, beri like
    $tanggallike = date('Y-m-d');
    $query = mysqli_query($koneksi, "INSERT INTO likefoto (fotoid, userid, tanggallike) VALUES('$fotoid', '$userid', '$tanggallike')");
    if ($query) {
        echo "<script>
        location.href='../admin/home.php?albumid=$albumid';
        </script>";
    } else {
        echo "<script>
        alert('Gagal menyukai foto.');
        location.href='../admin/home.php?albumid=$albumid';
        </script>";
    }
}
?>
