<?php 
session_start();
include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $namaalbum = $_POST['namaalbum'];
    $deskripsi = $_POST['deskripsi']; // Ensure this matches the form field name
    $tanggal = date('Y-m-d');
    $userid = $_SESSION['userid'];

    // Explicitly specify the columns being inserted into
    $sql = mysqli_query($koneksi, "INSERT INTO album (namaalbum, deskripsi, tanggaldibuat, userid) VALUES ('$namaalbum', '$deskripsi', '$tanggal', '$userid')");

    if ($sql) {
        echo "<script>
        alert('Data berhasil disimpan!');
        location.href='../admin/album.php';
        </script>";
    } else {
        echo "<script>
        alert('Data gagal disimpan!');
        location.href='../admin/album.php';
        </script>";
    }
}


if (isset($_POST['edit'])) {
    $albumid = $_POST['albumid'];
    $namaalbum = $_POST['namaalbum'];
    $deskripsi = $_POST['deskripsi']; // Ensure this matches the form field name
    $tanggal = date('Y-m-d');
    $userid = $_SESSION['userid'];

    // Explicitly specify the columns being updated
    $sql = mysqli_query($koneksi, "UPDATE album SET namaalbum='$namaalbum', deskripsi='$deskripsi', tanggaldibuat='$tanggal' WHERE albumid='$albumid'");

    if ($sql) {
        echo "<script>
        alert('Data berhasil diperbarui!');
        location.href='../admin/album.php';
        </script>";
    } else {
        echo "<script>
        alert('Data gagal diperbarui!');
        location.href='../admin/album.php';
        </script>";
    }
}

if (isset($_POST['hapus'])) {
    $albumid = $_POST['albumid'];
    
    if (isset($_POST['hapus'])) {
        $albumid = $_POST['albumid'];
    
        // Hapus semua foto yang terkait dengan album
        $hapusFoto = mysqli_query($koneksi, "DELETE FROM foto WHERE albumid='$albumid'");
    
        if ($hapusFoto) {
            // Jika foto berhasil dihapus, hapus albumnya
            $hapusAlbum = mysqli_query($koneksi, "DELETE FROM album WHERE albumid='$albumid'");
    
            if ($hapusAlbum) {
                echo "<script>
                alert('Album dan foto berhasil dihapus!');
                location.href='../admin/album.php';
                </script>";
            } else {
                echo "<script>
                alert('Album gagal dihapus!');
                location.href='../admin/album.php';
                </script>";
            }
        } else {
            echo "<script>
            alert('Gagal menghapus foto dalam album!');
            location.href='../admin/album.php';
            </script>";
        }
    }
    

    $sql = mysqli_query($koneksi, "DELETE FROM album WHERE albumid='$albumid'");

    if ($sql) {
        echo "<script>
        alert('Data berhasil dihapus!');
        location.href='../admin/album.php';
        </script>";
    } else {
        echo "<script>
        alert('Data gagal dihapus!');
        location.href='../admin/album.php';
        </script>";
    }
}
?>
