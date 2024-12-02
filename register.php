<?php
require_once "config.php";

$nama = $nomor_telepon = $email = $password = "";
$nama_err = $nomor_telepon_err = $email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validasi nama
    if (empty(trim($_POST["nama"]))) {
        $nama_err = "Masukkan Nama.";
    } else {
        $nama = trim($_POST["nama"]);
    }

    // Validasi nomor telepon
    if (empty(trim($_POST["nomor_telepon"]))) {
        $nomor_telepon_err = "Masukkan Nomor Telepon.";
    } else {
        $nomor_telepon = trim($_POST["nomor_telepon"]);
    }

    // Validasi email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Masukkan email.";
    } else {
        $email = trim($_POST["email"]);
        $sql = "SELECT nomor_telepon FROM user WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "Email sudah terdaftar.";
                }
            } else {
                echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validasi password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Masukkan password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password harus memiliki minimal 6 karakter.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Jika tidak ada error, simpan data ke database
    if (empty($nomor_telepon_err) && empty($nama_err) && empty($email_err) && empty($password_err)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query untuk insert data
        $sql = "INSERT INTO user (nama, email, password, nomor_telepon) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $param_nama, $param_email, $param_password, $param_nomor_telepon);
            $param_nama = $nama;
            $param_email = $email;
            $param_password = $hashed_password; // Simpan password yang sudah di-hash
            $param_nomor_telepon = $nomor_telepon;

            // Eksekusi query
            if (mysqli_stmt_execute($stmt)) {
                $message = "Akun berhasil dibuat";
                $message = urlencode($message);
                header("location: index.php?message={$message}");
                exit();
            } else {
                echo "Oops! Ada yang salah. Silakan coba lagi nanti.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwetiau Djuara - Register</title>
    <link rel="icon" type="image" href="https://i.imgur.com/uTgr4G3.jpeg">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="home.php">
                    <img src="https://i.imgur.com/uTgr4G3.jpeg" alt="Logo" class="logo-img">
                </a>
                <a href="home.php" class="brand"></a>
            </div>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="index.php" class="active">Reservasi</a></li>
                <li><a href="#my-reservasi">My Reservasi</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="contact.php">Kontak</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
        </nav>
    </header>
    <section class="res" id="home">
        <div class="res-c">
            <h1>Reservasi</h1>
        </div>
    </section>
    <section class="rus">
        <div>
            <h3>Register</h3><br>
            <p class="stupid"><b>Feel free to contact us anytime. We will get back to you as soon as we can!</b></p>
        </div>
        <div class="rus-c">
            <form method="post" action="register.php">
                <label>Name</label><br/>
                <input type="text" name="nama" placeholder="Nama" value="<?php echo htmlspecialchars($nama); ?>"/>
                <span class="error-message"><?php echo $nama_err; ?></span><br/><br/>
                <label>Phone Number</label><br/>
                <input type="number" name="nomor_telepon" placeholder="Nomor_telepon" value="<?php echo htmlspecialchars($nomor_telepon); ?>"/>
                <span class="error-message"><?php echo $nomor_telepon_err; ?></span><br/><br/>
                <label>E-mail</label><br/>
                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>"/>
                <span class="error-message"><?php echo $email_err; ?></span><br/><br/>
                <label>Password</label><br/>
                <input type="password" name="password" placeholder="Password" id="myInput" value="<?php echo htmlspecialchars($password); ?>"/>
                <span class="error-message"><?php echo $password_err; ?></span><br/>
                <input type="checkbox" onclick="myFunction()"> Show Password 
                <p>Sudah punya akun? <a href="index.php">Login di sini</a>.</p><br><br><br>
                <input type="submit" name="register" value="Register" style="background-color: #ff7f50; font-weight: bold;"/>
                <script>
                    function myFunction() {
                      var x = document.getElementById("myInput");
                      if (x.type === "password") {
                        x.type = "text";
                      } else {
                        x.type = "password";
                      }
                    }
                </script>
            </form>        
        </div>
    </section>
</body>
</html>