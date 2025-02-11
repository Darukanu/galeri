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

$userid = $_SESSION['userid'];

if (isset($_POST['tambah'])) {
    $judulfoto = $_POST['judulfoto'];
    $deskripsi = $_POST['deskripsi'];
    $tanggalunggah = date('Y-m-d');

    // Cek apakah user memilih album yang ada atau membuat album baru
    $albumid = $_POST['albumid'];
    $new_album = $_POST['new_album'];

    if (!empty($new_album)) {
        // Buat album baru
        $stmt_album = $koneksi->prepare("INSERT INTO album (namaalbum, userid, tanggaldibuat) VALUES (?, ?, ?)");
        $stmt_album->bind_param("sss", $new_album, $userid, $tanggalunggah);
        $stmt_album->execute();
        $albumid = $stmt_album->insert_id; // Dapatkan albumid dari album yang baru dibuat
    }

    // Cek apakah ada file yang diupload
    if ($_FILES['lokasifile']['error'] == UPLOAD_ERR_OK) {
        $lokasi_file = $_FILES['lokasifile']['tmp_name'];
        $nama_file = basename($_FILES['lokasifile']['name']);
        $tujuan = "../assets/img/" . $nama_file;

        if (move_uploaded_file($lokasi_file, $tujuan)) {
            // Simpan data foto ke database
            $stmt_foto = $koneksi->prepare("INSERT INTO foto (judulfoto, deskripsifoto, tanggalunggah, lokasifile, userid, albumid) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_foto->bind_param("ssssss", $judulfoto, $deskripsi, $tanggalunggah, $nama_file, $userid, $albumid);
            $stmt_foto->execute();

            echo "<script>alert('Foto berhasil diunggah!'); location.href='foto.php';</script>";
        } else {
            echo "<script>alert('Gagal mengunggah file!'); location.href='foto.php';</script>";
        }
    } else {
        echo "<script>alert('Tidak ada file yang diunggah!'); location.href='foto.php';</script>";
    }
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
                    <div class="card-header">Tambah Foto</div>
                    <div class="card-body">
                        <form action="foto.php" method="POST" enctype="multipart/form-data">
                            <label for="judulfoto" class="form-label">Judul Foto</label>
                            <input type="text" name="judulfoto" class="form-control" required>

                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" required></textarea>

                            <label for="albumid" class="form-label">Pilih Album</label>
                            <select name="albumid" class="form-control">
                                <option value="">-- Pilih Album --</option>
                                <?php
                                $stmt = $koneksi->prepare("SELECT * FROM album WHERE userid = ?");
                                $stmt->bind_param("s", $userid);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($data_album = $result->fetch_assoc()) { ?>
                                    <option value="<?php echo $data_album['albumid'] ?>"><?php echo $data_album['namaalbum'] ?></option>
                                <?php } ?>
                            </select>

                            <label for="new_album" class="form-label mt-2">Atau Buat Album Baru</label>
                            <input type="text" name="new_album" class="form-control" placeholder="Nama Album Baru">

                            <label for="lokasifile" class="form-label">File</label>
                            <input type="file" class="form-control" name="lokasifile" required>

                            <button type="submit" class="btn btn-primary mt-2" name="tambah">Tambah Data</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mt-2">
                    <div class="card-header">Data Galeri Foto</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>Judul Foto</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $stmt = $koneksi->prepare("SELECT * FROM foto WHERE userid = ?");
                                $stmt->bind_param("s", $userid);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($data = $result->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><img src="../assets/img/<?php echo $data['lokasifile'] ?>" width="150" alt=""></td>
                                        <td><?php echo $data['judulfoto']; ?></td>
                                        <td><?php echo $data['deskripsifoto']; ?></td>
                                        <td><?php echo $data['tanggalunggah']; ?></td>
                                        <td>
                                            <!-- Form Edit -->
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?php echo $data['fotoid'] ?>">
                                                Edit
                                            </button>

                                            <div class="modal fade" id="edit<?php echo $data['fotoid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="../config/aksi_foto.php" method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>">
                                                                <label for="judulfoto" class="form-label">Judul Foto</label>
                                                                <input type="text" name="judulfoto" value="<?php echo $data['judulfoto'] ?>" class="form-control" required>
                                                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                                                <textarea class="form-control" name="deskripsifoto" required><?php echo $data['deskripsifoto']; ?></textarea>
                                                                <label for="albumid" class="form-label">Album</label>
                                                                <select name="albumid" class="form-control">
                                                                    <?php
                                                                    $stmt_album = $koneksi->prepare("SELECT * FROM album WHERE userid = ?");
                                                                    $stmt_album->bind_param("s", $userid);
                                                                    $stmt_album->execute();
                                                                    $result_album = $stmt_album->get_result();
                                                                    while ($data_album = $result_album->fetch_assoc()) { ?>
                                                                        <option <?php if ($data_album['albumid'] == $data['albumid']) { ?> selected="selected" <?php } ?> value="<?php echo $data_album['albumid'] ?>"><?php echo $data_album['namaalbum'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <img src="../assets/img/<?php echo $data['lokasifile'] ?>" width="100" alt="">
                                                            </div>
                                                            <div class="col-md-8">
                                                                <label class="form-label">Ganti File</label>
                                                                <input type="file" class="form-control" name="lokasifile">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="edit" class="btn btn-primary">Edit Data</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Form Hapus -->
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapus<?php echo $data['fotoid'] ?>">
                                                Hapus
                                            </button>

                                            <div class="modal fade" id="hapus<?php echo $data['fotoid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="../config/aksi_foto.php" method="POST">
                                                                <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>">
                                                                Apakah anda yakin akan menghapus data <strong><?php echo $data['judulfoto'] ?></strong>?
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
