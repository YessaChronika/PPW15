<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwetiau Djuara</title>
    <link rel="icon" type="image" href="https://i.imgur.com/uTgr4G3.jpeg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <?php echo '<link href="styles.css" rel="stylesheet">'; ?>
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
                <li><a href="my-reservasi.php">My Reservasi</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="contact.php">Kontak</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
        </nav>

        <?php
    if (isset($_GET["message"])) {
        echo "<div class=\"alert alert-success my3\">".$_GET["message"]."</div>";
    }
    ?>
    </header>
    <section class="res" id="home">
        <div class="res-c">
            <h1>Reservasi</h1>
        </div>
    </section>
    <section class="rus">
        <div>
            <h3>Login</h3><br>
            <p class="stupid"><b>Feel free to contact us anytime. We will get back to you as soon as we can!</b></p>
        </div>
        <div class="rus-c">
            <form method="post" action="login.php">
                <label>E-mail</label><br/>
                <input type="email" name="email" placeholder="Email" /><br/><br/>
                <label>Password</label><br/>
                <input type="password" name="password" placeholder="Password" id="myInput"/><br/>
                <input type="checkbox" onclick="myFunction()"> Show Password
                <div class="hyperlink"><a href="register.php">Create account</a></div><br><br>
                <?php
                // cek apakah terdapat cookie dengan nama message
                if(isset($_COOKIE["message"])){// jika ada
                echo $_COOKIE["message"];// tampilkan pesannya
                }
                ?>
                <input type="submit" name="login" value="Login" style="background-color: #ff7f50; font-weight: bold;"/>
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