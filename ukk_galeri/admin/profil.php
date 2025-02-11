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
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE userid='$userid'");
$user = mysqli_fetch_assoc($query);

// Fungsi untuk menghapus akun dengan verifikasi password
if (isset($_POST['delete_account'])) {
    $confirm_password = $_POST['confirm_password'];

    // Verifikasi password sebelum menghapus akun
    if (password_verify($confirm_password, $user['password'])) {
        mysqli_query($koneksi, "DELETE FROM foto WHERE userid='$userid'");
        mysqli_query($koneksi, "DELETE FROM album WHERE userid='$userid'");
        $deleteQuery = mysqli_query($koneksi, "DELETE FROM user WHERE userid='$userid'");

        if ($deleteQuery) {
            session_destroy();
            echo "<script>
            alert('Akun berhasil dihapus.');
            location.href='../login.php';
            </script>";
        } else {
            echo "<script>
            alert('Gagal menghapus akun.');
            location.href='profil.php';
            </script>";
        }
    } else {
        echo "<script>
        alert('Password salah. Akun tidak dihapus.');
        location.href='profil.php';
        </script>";
    }
}

// Fungsi untuk update profil
if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $namalengkap = $_POST['namalengkap'];
    $alamat = $_POST['alamat'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $updateQuery = mysqli_query($koneksi, 
            "UPDATE user SET username='$username', email='$email', namalengkap='$namalengkap', alamat='$alamat', password='$hashed_password' WHERE userid='$userid'");
    } else {
        $updateQuery = mysqli_query($koneksi, 
            "UPDATE user SET username='$username', email='$email', namalengkap='$namalengkap', alamat='$alamat' WHERE userid='$userid'");
    }

    if ($updateQuery) {
        echo "<script>
        alert('Profil berhasil diperbarui!');
        location.href='profil.php';
        </script>";
    } else {
        echo "<script>
        alert('Gagal memperbarui profil.');
        location.href='profil.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Anda</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const showPasswordCheckbox = document.getElementById('showPassword');

        if (showPasswordCheckbox.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    }
    </script>

    <style>
        body {
            margin: 0;
            background-color: #6EACDA;
            justify-content: center;
        }
        .container-prof {
            margin-top: 70px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
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

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body bg-light">
                    <div class="text-center">
                        <h2>Profil Kamu</h2>
                    </div>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="namalengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="namalengkap" name="namalengkap" value="<?php echo htmlspecialchars($user['namalengkap']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" required><?php echo htmlspecialchars($user['alamat']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password (Opsional)">
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
                                <label class="form-check-label" for="showPassword">Lihat Password</label>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="update" class="btn btn-primary">Perbarui Profil</button>
                        </div>
                    </form>

                    <!-- Tombol Hapus Akun -->
                    <button class="btn btn-danger mt-4" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        Hapus Akun
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Akun -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <p>Masukkan password Anda untuk mengkonfirmasi penghapusan akun:</p>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="delete_account" class="btn btn-danger">Hapus Akun</button>
                    </div>
                </div>
            </form>
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

<script src="../assets/js/bootstrap.bundle.min.js">
</script>
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
