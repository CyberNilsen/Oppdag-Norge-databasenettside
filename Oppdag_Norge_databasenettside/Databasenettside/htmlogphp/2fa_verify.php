    <?php
    session_start();

    // Sjekk om brukeren er logget inn og om 2FA-koden er satt
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['pending_user_email'])) {
        header("Location: login.php");
        exit();
    }

    $two_fa_code = $_SESSION['2fa_code'] ?? '';
    $entered_code = $_POST['two_fa_code'] ?? '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($entered_code == $two_fa_code) {
            // 2FA er bekreftet
            unset($_SESSION['2fa_code']); // Fjern 2FA-koden fra session

            // Send brukeren til hovedsiden
            header("Location: ../../index.php");
            exit();
        } else {
            $error_message = "Feil 2FA-kode.";
        }
    }
    ?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifiser 2FA</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="../../index.php" class="logo-link"><img src="../bilder/OppdagNorgemindre.png" alt="Oppdag Norge" class="logo"></a>
            <nav>
                <ul>
                    <li><a href="fjorder.html">Fjorder</a></li>
                    <li><a href="fjell.html">Fjell</a></li>
                    <li><a href="byer.html">Byer</a></li>
                    <li><a href="om-oss.html">Om Oss</a></li>
                    <li><a href="login.php">Login</a></li>
                    
                </ul>
            </nav>
        </div>
    </header>

    <div class="form-container">
        <h2>Verifiser 2FA-kode</h2>
        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="two_fa_code">2FA-kode</label>
                <input type="text" name="two_fa_code" id="two_fa_code" required>
            </div>
            <button type="submit" class="btn">Verifiser</button>
        </form>
    </div>
</body>
</html>
