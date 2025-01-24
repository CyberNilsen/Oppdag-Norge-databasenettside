<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

require '../vendor/autoload.php';

use SendGrid\Mail\Mail;
use Dotenv\Dotenv;

$verification_error = '';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../', 'token.env');
$dotenv->load();

// Send 2FA email
function send_2fa_email($email, $code) {
    $email_send = new Mail();
    $email_send->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
    $email_send->setSubject('Din 2FA-kode');
    $email_send->addTo($email);
    $email_send->addContent("text/plain", "Din 2FA-kode er: $code");
    $email_send->addContent("text/html", "<strong>Din 2FA-kode er: $code</strong>");

    $sendgrid = new \SendGrid($_ENV['SENDGRID_API_KEY']);
    try {
        $response = $sendgrid->send($email_send);
        if ($response->statusCode() != 202) {
            echo "Feil ved sending av e-post: " . $response->statusCode();
        }
    } catch (Exception $e) {
        echo 'Feil ved sending av e-post: ' . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify'])) {
    $entered_code = $_POST['code'];

    if (isset($_SESSION['2fa_code']) && $entered_code == $_SESSION['2fa_code']) {
        $email = $_SESSION['pending_user_email'];
        unset($_SESSION['2fa_code']);
        unset($_SESSION['pending_user_email']);
        $_SESSION['user_id'] = 1; // Example user_id
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $user['name'];
        header("Location: ../../index.php");
        exit();
    } else {
        $verification_error = "Feil 2FA-kode.";
    }
}

// Resend functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resend_code'])) {
    if (isset($_SESSION['last_sent_time']) && (time() - $_SESSION['last_sent_time']) < 30) {
        // If 30 seconds haven't passed, show an error
        $verification_error = "Du må vente 30 sekunder før du kan sende en ny kode.";
    } else {
        // Generate a new code and send email
        $code = rand(100000, 999999);
        $_SESSION['2fa_code'] = $code;

        $email = $_SESSION['pending_user_email'];
        send_2fa_email($email, $code);

        // Store the time when the code was sent
        $_SESSION['last_sent_time'] = time();
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

    <!-- 2FA verifikasjons Form -->
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

        <form method="POST">
            <button type="submit" name="resend_code" class="btn">Send ny kode</button>
        </form>

    </div>
</body>

</html>

