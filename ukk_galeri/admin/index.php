<?php
session_start();
include '../config/koneksi.php'; // Pastikan file koneksi sudah benar

$userid = $_SESSION['userid'];
if ($_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda belum login!');
    location.href='../login.php';
    </script>";
    exit;
}

// Ambil data user dari database
$query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE userid='$userid'");
$user = mysqli_fetch_assoc($query_user);

// Ambil data foto dari database berdasarkan userid
$query = mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid' ORDER BY tanggalunggah DESC");
$fotos = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Galeri</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background-color: #6EACDA;
            justify-content: center;
        }
        .gallery-item {
            margin-bottom: 20px;
        }
        .foto-info {
            text-align: center;
            margin-top: 10px;
        }
        .foto-home{
            margin-left: 10%;
            margin-right: 10%;
        }
        .profile-info {
            text-align: center;
            margin-bottom: 20px;
        }
        footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
            text-align: center;
        }
        .card-title {
            margin-bottom: 10px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .card-text {
            color: #555;
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

<section class="py-4 foto-home">
    <div class="container d-flex flex-column ">
        <div class="profile-info">
            <h3>Selamat Datang, <?php echo htmlspecialchars($user['username']); ?>!</h3>
        </div>

        <h2 class="text-center mb-4">Galeri Foto Anda</h2>
    
        <?php if (count($fotos) > 0): ?>
            <div class="row g-4">
                <?php foreach ($fotos as $foto): ?>
                    <div class="col-lg-3 col-sm-6 col-12 gallery-item">
                        <div class="card">
                            <img src="../assets/img/<?php echo $foto['lokasifile']; ?>" alt="<?php echo $foto['judulfoto']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($foto['judulfoto']); ?></h5>
                                <p class="card-text"><?php echo date('d M Y', strtotime($foto['tanggalunggah'])); ?></p>
                                <a href="detail_foto.php?id=<?php echo $foto['fotoid']; ?>" class="btn btn-sm btn-info mt-2">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center mt-5">
                <p>Belum ada foto yang diunggah. <br>
                <a href="foto.php" class="btn btn-primary mt-2">Tambah Foto Kamu Sekarang</a></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<footer class="footer mt-4 py-3">
    <div class="container text-center">
        <p class="mb-1 text-light">&copy; UKK 2025 | <strong>MUHAMMAD ZIDAN ROZAKY</strong></p>
        <a href="https://trakteer.id/akize" target="_blank" class="btn btn-light btn-sm shadow-sm">
            ☕ Dukung Kami
        </a>
    </div>
</footer>

<style>
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
