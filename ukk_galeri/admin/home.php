    <?php
    session_start();
    if (!isset($_SESSION['userid']) || $_SESSION['status'] != 'login') {
        echo "<script>
        alert('Anda belum login!');
        location.href='../login.php';
        </script>";
        exit;
    }

    $userid = $_SESSION['userid'];
    include '../config/koneksi.php';

    // Ambil albumid dari parameter URL
    $albumid = isset($_GET['albumid']) ? $_GET['albumid'] : '';


    // Query untuk mengambil album
    $album_query = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Website Galeri</title>
        <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
        <style>
            body {
                margin-bottom: 70px; /* Tambahkan margin bawah untuk ruang footer */
                background-color: #6EACDA;
                justify-content: center;
            }

           

            .modal-dialog-scrollable .modal-content {
                max-height: calc(100vh - 100px);
            }

            .card:hover {
                transform: scale(1.05);
                transition: transform 0.3s ease;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            }

            .card-img-top {
                width: 100%;
                height: 300px;
                object-fit: cover; /* Memastikan gambar menutupi area yang ditentukan */
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

    <div class="container mt-3">
    <div class="mb-3">
        Album kamu:
        <!-- Tambahkan tombol "Lihat Semua Foto" hanya jika album sudah dipilih -->
        <?php if (!empty($albumid)) { ?>
            <a href="home.php" class="btn btn-outline-primary">Lihat Semua Foto</a>
        <?php } ?>
        <?php while ($row = mysqli_fetch_array($album_query)) { ?>
            <a href="home.php?albumid=<?php echo $row['albumid'] ?>" class="btn btn-outline-primary"> <?php echo $row['namaalbum'] ?> </a>
        <?php } ?>

        
    </div>

    <div class="row">
    <?php
    $foto_query = (!empty($albumid)) 
        ? mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid' AND albumid='$albumid'") 
        : mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid'");

    while ($data = mysqli_fetch_array($foto_query)) {
    ?>
        <div class="col-md-3">
            <div class="card">
                <img src="../assets/img/<?php echo $data['lokasifile'] ?>" class="card-img-top" title="<?php echo $data['judulfoto'] ?>" style="width: 100%; height: 300px; object-fit: cover;">
                <div class="card-footer text-center">
                    <!-- Tambahkan judul foto sebelum fitur favorit -->
                    <p class="fw-bold"><?php echo $data['judulfoto']; ?></p>

                    <?php
                    $fotoid = $data['fotoid'];
                             
                    $ceksuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");

                    if (mysqli_num_rows($ceksuka) == 1) { ?>
                        <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>&albumid=<?php echo $albumid ?>"><i class="fa fa-star text-warning"></i> Favorit</a>
                    <?php } else { ?>
                        <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>&albumid=<?php echo $albumid ?>"><i class="fa-regular fa-star"></i> Tambah ke Favorit</a>
                    <?php } ?>
                    <br>
                    <a href="detail_foto_home.php?id=<?php echo $data['fotoid']; ?>" class="btn btn-sm btn-info mt-2">Lihat Detail</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>


<footer class="footer mt-4 py-3">
    <div class="text-center">
        <p class="mb-1 text-light">&copy; UKK 2025 | <strong>MUHAMMAD ZIDAN ROZAKY</strong></p>
        <a href="https://trakteer.id/akize" target="_blank" class="btn btn-light btn-sm shadow-sm">
            ☕ Dukung Kami
        </a>
    </div>
</footer>

<style>
    body {
        padding-bottom: 80px; /* Mencegah konten tertutup footer */
    }

    .footer {
        background: linear-gradient(135deg, #3E92CC, #2B6CB0);
        color: white;
        font-size: 14px;
        text-align: center;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.2);
        display: flex;
        justify-content: center; /* Pastikan isi footer berada di tengah */
        align-items: center;
        padding: 10px 0;
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



    <script>
    document.getElementById('logout-btn').addEventListener('click', function(event) {
        const confirmation = confirm('Apakah Anda yakin ingin logout?');
        if (!confirmation) {
            event.preventDefault();
        }
    });
    </script>
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>

    </body>
    </html>
