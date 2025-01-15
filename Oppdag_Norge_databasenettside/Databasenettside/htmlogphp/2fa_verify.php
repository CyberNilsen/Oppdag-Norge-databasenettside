<?php
session_start();
require '../vendor/autoload.php';

use Dotenv\Dotenv;

$verification_error = '';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../', 'token.env');
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify'])) {
    $entered_code = $_POST['code'];

    if (isset($_SESSION['2fa_code']) && $entered_code == $_SESSION['2fa_code']) {
        $email = $_SESSION['pending_user_email'];
        unset($_SESSION['2fa_code']);
        unset($_SESSION['pending_user_email']);

        $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, name, email FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: ../../index.php");
            exit();
        } else {
            $verification_error = "Brukeren finnes ikke.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $verification_error = "Feil 2FA-kode.";
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verifisering</title>
    <link rel="icon" type="image/x-icon" href="../bilder/OppdagNorge.png">
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
                    <?php if (isset($_SESSION['user_email'])): ?>
                        <li><a href="profil.php">Profil</a></li>
                        <li><a href="../htmlogphp/logut.php">Logg ut</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- 2FA Verification Form -->
    <div class="form-container">
        <h2>Verifiser 2FA</h2>
        <?php if ($verification_error): ?>
            <p style="color: red;"><?php echo $verification_error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="code">Skriv inn 2FA-koden</label>
                <input type="text" name="code" id="code" placeholder="Skriv inn koden" required>
            </div>
            <button type="submit" name="verify" class="btn">Verifiser</button>
        </form>
    </div>
</body>
</html>
