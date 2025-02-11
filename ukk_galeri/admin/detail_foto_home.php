<?php
session_start();
include '../config/koneksi.php'; // Pastikan koneksi benar

// Cek apakah user sudah login
if ($_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda belum login!');
    location.href='../login.php';
    </script>";
    exit;
}

// Ambil ID foto dari URL
if (isset($_GET['id'])) {
    $fotoid = $_GET['id'];

    // Ambil data foto dari database berdasarkan ID
    $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE fotoid='$fotoid'");
    $foto = mysqli_fetch_assoc($query);

    // Jika foto tidak ditemukan
    if (!$foto) {
        echo "<script>
        alert('Foto tidak ditemukan!');
        location.href='home.php';
        </script>";
        exit;
    }
} else {
    echo "<script>
    alert('ID Foto tidak valid!');
    location.href='home.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Foto - <?php echo htmlspecialchars($foto['judulfoto']); ?></title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #6EACDA;
        }
        .detail-container {
            margin-top: 50px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
        }
        .detail-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .download-btn {
    position: fixed;
    bottom: 20px;  /* Jarak dari bawah */
    right: 20px;   /* Jarak dari kanan */
    padding: 10px 20px;
    border-radius: 50px;  /* Membuat sudut tombol lebih membulat */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);  /* Menambahkan bayangan */
    z-index: 1000;  /* Memastikan tombol di atas elemen lain */
}

.download-btn:hover {
    background-color: #28a745;  /* Warna lebih gelap saat hover */
    color: white;
}
.navbar {
        background: linear-gradient(135deg, #6EACDA, #3E92CC);
        padding: 12px 0;
    }
    .nav-link {
        transition: 0.3s;
    }
    .nav-link:hover {
        transform: scale(1.1);
        color: #FFD700 !important;
    }
    .btn {
        transition: all 0.3s ease-in-out;
    }
    .btn:hover {
        transform: scale(1.05);
    }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-gradient shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fas fa-camera-retro"></i> Galeri Foto
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link text-light fw-semibold px-3" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-semibold px-3" href="album.php">Album</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-semibold px-3" href="foto.php">Foto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-semibold px-3 active" href="favorit.php">
                        <i class="fas fa-heart text-danger"></i> Favorit
                    </a>
                </li>
            </ul>
            <div class="d-flex">
                <a href="profil.php" class="btn btn-outline-light me-2">
                    <i class="fas fa-user"></i> Profil
                </a>
                <a href="../config/aksi_logout.php" class="btn btn-danger" id="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container detail-container">
    <h2 class="text-center"><?php echo htmlspecialchars($foto['judulfoto']); ?></h2>
    <img src="../assets/img/<?php echo $foto['lokasifile']; ?>" class="detail-image" alt="<?php echo htmlspecialchars($foto['judulfoto']); ?>">
    
    <div class="mt-4">
        <p><strong>Tanggal Unggah:</strong> <?php echo date('d M Y', strtotime($foto['tanggalunggah'])); ?></p>
        <p><strong>Deskripsi:</strong> <?php echo nl2br(htmlspecialchars($foto['deskripsifoto'])); ?></p>
    </div>

    <a href="home.php" class="btn btn-secondary mt-3">Kembali</a>
    <!-- Tombol Download yang selalu berada di pojok kanan bawah -->
<a href="../assets/img/<?php echo $foto['lokasifile']; ?>" download="<?php echo htmlspecialchars($foto['judulfoto']); ?>" 
   class="btn btn-success download-btn">
    Download Foto
</a>
</div>

<footer class="footer mt-4 py-3">
    <div class="container text-center">
        <p class="mb-1 text-light">&copy; UKK 2025 | <strong>MUHAMMAD ZIDAN ROZAKY</strong></p>
        <a href="https://trakteer.id/akize" target="_blank" class="btn btn-light btn-sm shadow-sm">
            ☕ Dukung Kami
        </a>
    </div>
</footer>

<style>
    .detail-container {
        margin-top: 50px;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        padding-bottom: 100px; /* Tambah padding agar tombol tidak tertutup footer */
    }

    .btn-back {
        margin-bottom: 80px; /* Memberikan jarak cukup dari footer */
    }

    .footer {
        background: linear-gradient(135deg, #3E92CC, #2B6CB0);
        color: white;
        font-size: 14px;
        text-align: center;
        position: fixed;
        bottom: 0;
        width: 100%;
        box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.2);
    }

    .footer a {
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .footer a:hover {
        transform: scale(1.1);
        color: #FFD700;
    }
</style>




<script src="../assets/js/bootstrap.min.js"></script>
<script>
    document.getElementById('logout-btn').addEventListener('click', function(event) {
        const confirmation = confirm('Apakah Anda yakin ingin logout?');
        if (!confirmation) {
            event.preventDefault();  // Mencegah logout jika user klik "Cancel"
        }
    });
</script>

</body>
</html>
