<?php
session_start();
include("config.php");

// Ambil data dari form
$email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
$password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';

if ($email != '' && $password != '') {
    // Query untuk cek data user berdasarkan email
    $sql = "SELECT * FROM user WHERE email='$email'";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query); // Ambil data user
        
        // Verifikasi password yang di-hash menggunakan password_verify()
        if (password_verify($password, $data['password'])) {
            // Jika password valid, set session
            $_SESSION['id_user'] = $data['id_user']; // Tambahkan session id_user
            $_SESSION['email'] = $data['email'];
            $_SESSION['nama'] = $data['nama'];
            
            // Hapus cookie pesan error
            setcookie("message", "", time() - 3600);

            // Redirect ke halaman reservasi
            header("Location: reservasi.php");
            exit(); // Hentikan eksekusi
        } else {
            // Jika password salah
            setcookie("message", "Maaf, email atau password salah", time() + 60);
            header("Location: index.php");
            exit();
        }
    } else {
        // Jika email tidak ditemukan
        setcookie("message", "Maaf, email atau password salah", time() + 60);
        header("Location: index.php");
        exit();
    }
} else {
    // Jika email atau password kosong
    setcookie("message", "Email atau Password kosong", time() + 60);
    header("Location: index.php");
    exit();
}
?>
