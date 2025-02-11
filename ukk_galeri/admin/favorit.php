<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
        alert('Anda belum login!');
        location.href='../login.php';
    </script>";
    exit;
}

$userid = $_SESSION['userid'];

// Mengambil foto yang disukai oleh user
$query = mysqli_query($koneksi, "SELECT f.* FROM foto f 
                                JOIN likefoto l ON f.fotoid = l.fotoid 
                                WHERE l.userid = '$userid' 
                                ORDER BY l.tanggallike DESC");
$favorit_fotos = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Favorit Anda</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body {
            background-color: #6EACDA;
            padding-bottom: 80px; /* Menghindari footer menutupi konten */
        }
        .gallery-item {
            margin-bottom: 20px;
        }
        .foto-info {
            text-align: center;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .foto-home {
            margin: 0 auto;
            max-width: 90%;
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card-img-top {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .navbar {
            background: linear-gradient(135deg, #6EACDA, #3E92CC);
        }
        .nav-link {
            transition: transform 0.3s, color 0.3s;
        }
        .nav-link:hover {
            transform: scale(1.1);
            color: #FFD700 !important;
        }
        .btn {
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: scale(1.05);
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
            padding: 10px;
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
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-gradient shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fas fa-camera-retro"></i> Galeri Foto
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link text-light fw-semibold px-3" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link text-light fw-semibold px-3" href="album.php">Album</a></li>
                <li class="nav-item"><a class="nav-link text-light fw-semibold px-3" href="foto.php">Foto</a></li>
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

<!-- Konten Foto Favorit -->
<section class="py-4 foto-home">
    <div class="container">
        <h2 class="text-center mb-4">Foto Favorit Anda</h2>

        <?php if (!empty($favorit_fotos)): ?>
            <div class="row g-4">
                <?php foreach ($favorit_fotos as $foto): ?>
                    <div class="col-lg-3 col-sm-6 col-12 gallery-item">
                        <div class="card h-100 shadow-sm">
                            <img class="card-img-top" src="../assets/img/<?= htmlspecialchars($foto['lokasifile']) ?>" alt="<?= htmlspecialchars($foto['judulfoto']) ?>">
                            <div class="foto-info">
                                <h5><?= htmlspecialchars($foto['judulfoto']) ?></h5>
                                <p><?= date('d M Y', strtotime($foto['tanggalunggah'])) ?></p>
                                <a href="detail_foto.php?id=<?= $foto['fotoid'] ?>" class="btn btn-sm btn-info mt-2">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center mt-5">
                <p>Belum ada foto yang Anda sukai.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container text-center">
        <p class="mb-1 text-light">&copy; UKK 2025 | <strong>MUHAMMAD ZIDAN ROZAKY</strong></p>
        <a href="https://trakteer.id/akize" target="_blank" class="btn btn-light btn-sm shadow-sm">
            ☕ Dukung Kami
        </a>
    </div>
</footer>

<script src="../assets/js/bootstrap.min.js"></script>
<script>
    document.getElementById('logout-btn').addEventListener('click', function(event) {
        if (!confirm('Apakah Anda yakin ingin logout?')) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>
