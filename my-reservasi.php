<?php
session_start();
include('config.php');

// Placeholder for fetched data
$orderDetails = [];
$totalAmount = 0;
$userName = 'Guest';
$reservationDate = '';
$reservationTime = '';
$duration = '90 Minutes'; // Default duration
$reservationFound = false;

// Fetch user information
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    
    // Fetch user name
    $sqlUser = "SELECT nama FROM user WHERE id_user = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("i", $id_user);
    $stmtUser->execute();
    $stmtUser->bind_result($userName);
    $stmtUser->fetch();
    $stmtUser->close();

    // Fetch latest reservation for the user
    $sqlReservasi = "SELECT tanggal_reservasi, waktu_reservasi FROM reservasi 
                     WHERE id_user = ? ORDER BY id_reservasi DESC LIMIT 1";
    $stmtReservasi = $conn->prepare($sqlReservasi);
    $stmtReservasi->bind_param("i", $id_user);
    $stmtReservasi->execute();
    $stmtReservasi->bind_result($reservationDate, $reservationTime);
    if ($stmtReservasi->fetch()) {
        $reservationFound = true; // Flag if a reservation is found
    }
    $stmtReservasi->close();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderDetails'])) {
    $orderDetails = json_decode($_POST['orderDetails'], true);

    if (!empty($orderDetails)) {
        // Fetch menu details from the database based on the submitted order
        $menuIds = array_column($orderDetails, 'id');
        $menuIdsString = implode(',', array_map('intval', $menuIds)); // Convert IDs to a safe string format

        $sql = "SELECT id_menu, nama_menu, deskripsi, harga, foto FROM menu WHERE id_menu IN ($menuIdsString)";
        $result = $conn->query($sql);

        // Map menu details with quantities from the submitted order
        $menuMap = [];
        while ($row = $result->fetch_assoc()) {
            $menuMap[$row['id_menu']] = $row;
            $menuMap[$row['id_menu']]['quantity'] = 0; // Initialize quantity
        }

        foreach ($orderDetails as $item) {
            if (isset($menuMap[$item['id']])) {
                $menuMap[$item['id']]['quantity'] += $item['quantity']; // Sum up the quantities
                $totalAmount += $menuMap[$item['id']]['harga'] * $item['quantity'];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservation</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="https://i.imgur.com/uTgr4G3.jpeg">
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
                <li><a href="index.php">Reservasi</a></li>
                <li><a href="my-reservasi.php"class="active">My Reservasi</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="contact.php">Kontak</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
        </nav>
    </header>
    <section class="res" id="home">
        <div class="res-c">
            <h1>Your Reservation<br>Will be Held at</h1>
        </div>
    </section>
    <section class="rus" id="home">
        <div class="rus-c">
            <fieldset>
                <!-- Reservation Details Section -->
                <div class="billreserv">
                    <div class="iden">
                        <div class="identitasku1">
                                <p><b>Name:</b> <?= htmlspecialchars($userName); ?></p>
                                <br/>
                                <br/>
                                <p><b>Duration:</b> <?= $duration; ?></p>
                        </div>
                        <div class="identitasku2">
                            <?php if ($reservationFound): ?>
                                <p><b>Date:</b> <?= htmlspecialchars($reservationDate); ?></p>
                                <br/>
                                <br/>
                                <p><b>Time:</b> <?= htmlspecialchars($reservationTime); ?></p>
                            <?php else: ?>
                                <p>No reservation details found.</p>
                            <?php endif; ?>
                        </div>    
                    </div>        
                </div> 
                <!-- Makanan Section -->
                <div class="menumakanan">
                    <div class="line-container">
                        <div class="line"></div><h2>Makanan</h2><div class="line"></div>
                    </div>
                       
                            <?php foreach ($menuMap as $item): ?>
                            <?php if (strpos(strtolower($item['nama_menu']), 'kwetiau') !== false): ?>
                                <div class="menumyres">
                                    <img src="<?= htmlspecialchars($item['foto']); ?>" alt="<?= htmlspecialchars($item['nama_menu']); ?>">
                                    <h3><?= htmlspecialchars($item['nama_menu']); ?></h3>
                                    <p><?= htmlspecialchars($item['deskripsi']); ?></p>
                                    <p class="price">
                                        <?= $item['quantity']; ?> x Rp<?= number_format($item['harga'], 2, ',', '.'); ?> = 
                                        Rp<?= number_format($item['harga'] * $item['quantity'], 2, ',', '.'); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                </div>
                <!-- Minuman Section -->
                <div class="menuminuman">
                    <div class="line-container">
                        <div class="line"></div><h2>Minuman</h2><div class="line"></div>
                    </div>
                    
                            <?php foreach ($menuMap as $item): ?>
                            <?php if (strpos(strtolower($item['nama_menu']), 'kwetiau') === false): ?>
                                <div class="menumyres">
                                    <img src="<?= htmlspecialchars($item['foto']); ?>" alt="<?= htmlspecialchars($item['nama_menu']); ?>">
                                    <h3><?= htmlspecialchars($item['nama_menu']); ?></h3>
                                    <p><?= htmlspecialchars($item['deskripsi']); ?></p>
                                        <p class="price">
                                            <?= $item['quantity']; ?> x Rp<?= number_format($item['harga'], 2, ',', '.'); ?> = 
                                            Rp<?= number_format($item['harga'] * $item['quantity'], 2, ',', '.'); ?>
                                        </p>
                                </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                </div>   
                <!-- Total Section -->
                <div class="totalmyres">
                    <div class="line-container">
                        <div class="line"></div><h2>Total</h2><div class="line"></div>
                    </div>
                    
                    <br/>
                    <br/>
                    <p class="price"><b>Total Amount:</b> Rp<?= number_format($totalAmount, 2, ',', '.'); ?></p>
                </div>
            </fieldset>
        </div>

        <div class="deleteupdatebutton">
            <button id="update" class="buttonmyres">Update</button>
            <button id="delete" class="buttonmyres">Delete</button>
        </div>

    </section>


</body>
</html>
