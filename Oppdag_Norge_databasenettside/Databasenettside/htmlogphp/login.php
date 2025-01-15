<?php
session_start();
require '../vendor/autoload.php';  // Sørg for at dette er riktig sti

use SendGrid\Mail\Mail;
use Dotenv\Dotenv;  // Importer Dotenv riktig

$login_error = '';
$register_error = '';  // Legg til en standardverdi
$email_register = isset($_POST['email_register']) ? $_POST['email_register'] : '';  // Hent fra POST eller tomt
$name_register = isset($_POST['name_register']) ? $_POST['name_register'] : '';  // Hent fra POST eller tomt
$password_register = ''; // Denne kan være tom til den settes i POST

$email = '';

// Last inn token.env-filen
$dotenv = Dotenv::createImmutable(__DIR__ . '/../', 'token.env');
$dotenv->load();

// Funksjon for å sende 2FA e-post med SendGrid
function send_2fa_email($email, $code) {
    $email_send = new Mail();
    $email_send->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
    $email_send->setSubject('Din 2FA-kode');
    $email_send->addTo($email);
    $email_send->addContent("text/plain", "Din 2FA-kode er: $code");
    $email_send->addContent("text/html", "<strong>Din 2FA-kode er: $code</strong>");

    // Send e-posten via SendGrid
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

// Innlogging
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Bruk $_ENV for å hente databaseinformasjon
    $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, name, email, password, two_fa_enabled FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];

            // Hvis 2FA er aktivert (alltid på), send til verifiseringsside
            $code = rand(100000, 999999);
            $_SESSION['2fa_code'] = $code; // Lagre 2FA-koden i session
            send_2fa_email($email, $code); // Send 2FA-kode

            $_SESSION['pending_user_email'] = $email;
            header("Location: 2fa_verify.php");
            exit();
        } else {
            $login_error = "Feil passord.";
        }
    } else {
        $login_error = "Brukeren finnes ikke.";
    }

    $stmt->close();
    $conn->close();
}

// Registrering
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name_register = $_POST['name_register'];
    $email_register = $_POST['email_register'];
    $password_register = $_POST['password_register'];

    // Bruk $_ENV for å hente databaseinformasjon
    $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_register);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $register_error = "Denne e-posten er allerede registrert.";
    } else {
        $hashed_password = password_hash($password_register, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, two_fa_enabled) VALUES (?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name_register, $email_register, $hashed_password);

        if ($stmt->execute()) {
            // Etter vellykket registrering, send til login-siden
            header("Location: login.php");
            exit();
        } else {
            $register_error = "Noe gikk galt. Prøv igjen.";
        }
    }

    $stmt->close();
    $conn->close();
}

?>


<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oppdag Norge - Logg Inn</title>
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
                        <li><a href="">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
                        
    <!-- Login Form -->
    <div class="form-container" id="login-form" class="hidden">
        <h2>Logg inn</h2>
        <?php if ($login_error): ?>
            <p style="color: red;"><?php echo $login_error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">E-post</label>
                <input type="email" name="email" id="email" placeholder="Skriv inn e-post" required value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="form-group">
                <label for="password">Passord</label>
                <input type="password" name="password" id="password" placeholder="Skriv inn passord" required>
            </div>
            <button type="submit" name="login" class="loginbtn">Logg inn</button>
        </form>
        <p>Har du ikke en konto? <a href="#" id="switchToRegister">Registrer deg</a></p>
    </div>

    <!-- Register Form -->
    <div class="form-container hidden" id="register-form">
        <h2>Registrer deg</h2>
        <?php if ($register_error): ?>
            <p style="color: red;"><?php echo $register_error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="name_register">Navn</label>
                <input type="text" name="name_register" id="name_register" placeholder="Skriv inn navn" required>
            </div>
            <div class="form-group">
                <label for="email_register">E-post</label>
                <input type="email" name="email_register" id="email_register" placeholder="Skriv inn e-post" required value="<?php echo htmlspecialchars($email_register); ?>">
            </div>
            <div class="form-group">
                <label for="password_register">Passord</label>
                <input type="password" name="password_register" id="password_register" placeholder="Skriv inn passord" required>
            </div>
            <div class="form-group">
                <label for="two_fa_method">Velg 2FA-metode</label>
                <select name="two_fa_method" id="two_fa_method" required>
                    <option value="email">E-post</option>
                    <option value="app">App (f.eks. Google Authenticator)</option>
                </select>
            </div>
            <button type="submit" name="register" class="btn">Registrer deg</button>
        </form>
        <p>Har du allerede en konto? <a href="#" id="switchToLogin">Logg inn</a></p>
    </div>

    <!-- JavaScript to switch forms -->
    <script>
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const switchToRegister = document.getElementById('switchToRegister');
        const switchToLogin = document.getElementById('switchToLogin');

        switchToRegister.addEventListener('click', (e) => {
            e.preventDefault();
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
        });

        switchToLogin.addEventListener('click', (e) => {
            e.preventDefault();
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
        });
    </script>
</body>
</html>
