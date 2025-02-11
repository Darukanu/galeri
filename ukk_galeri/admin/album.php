<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda belum login!');
    location.href='../login.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Galeri</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
</head>
<style>
     body {
            margin: 0;
            background-color: #6EACDA;
            justify-content: center;
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

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card mt-2">
                    <div class="card-header">Tambah Album</div>
                    <div class="card-body">
                        <form action="../config/aksi_album.php" method="POST">
                            <label for="namaalbum" class="form-label">Nama Album</label>
                            <input type="text" name="namaalbum" class="form-control" required>
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" required></textarea>
                            <button type="submit" class="btn btn-primary mt-2" name="tambah">Tambah Data</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mt-2">
                    <div class="card-header">Data Album</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Album</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $userid = $_SESSION['userid'];
                                $sql = mysqli_query($koneksi, "SELECT * FROM album WHERE userid = '$userid'");
                                while ($data = mysqli_fetch_array($sql)) {
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $data['namaalbum']; ?></td>
                                        <td><?php echo $data['deskripsi']; ?></td>
                                        <td><?php echo $data['tanggaldibuat']; ?></td>
                                        <td>
                                            <!-- Form Edit -->
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?php echo $data['albumid'] ?>">
                                                Edit
                                            </button>

                                            <div class="modal fade" id="edit<?php echo $data['albumid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="../config/aksi_album.php" method="POST">
                                                                <input type="hidden" name="albumid" value="<?php echo $data['albumid'] ?>">
                                                                <label for="namaalbum" class="form-label">Nama Album</label>
                                                                <input type="text" name="namaalbum" value="<?php echo $data['namaalbum']; ?>" class="form-control" required>
                                                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                                                <textarea class="form-control" name="deskripsi" required><?php echo $data['deskripsi']; ?></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="edit" class="btn btn-primary">Edit Data</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Form Hapus -->
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapus<?php echo $data['albumid'] ?>">
                                                Hapus
                                            </button>

                                            <div class="modal fade" id="hapus<?php echo $data['albumid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="../config/aksi_album.php" method="POST">
                                                                <input type="hidden" name="albumid" value="<?php echo $data['albumid'] ?>">
                                                                Apakah anda yakin akan menghapus data <strong><?php echo $data['namaalbum'] ?></strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="hapus" class="btn btn-danger">Hapus Data</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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


    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
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
