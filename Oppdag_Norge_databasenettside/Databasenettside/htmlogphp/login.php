<?php
session_start();  // Starter en ny session eller gjenoppretter en eksisterende

$login_error = '';  // Variabel for å lagre feilmeldinger ved pålogging
$register_error = '';  // Variabel for registreringsfeil
// Inkluder databaseforbindelsen
require_once 'dbconfig.php';  // Sti til db_config.php hvis filen er plassert utenfor html-mappen

// Håndterer registrering
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Hent data fra registreringsskjemaet
    $email_register = $_POST['email_register'];
    $password_register = $_POST['password_register'];

    // Koble til databasen
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Sjekk om forbindelsen til databasen er vellykket
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sjekk om e-posten allerede er registrert
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_register);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $register_error = "Denne e-posten er allerede registrert.";
    } else {
        // Hash passordet før lagring i databasen
        $hashed_password = password_hash($password_register, PASSWORD_DEFAULT);

        // Sett inn den nye brukeren i databasen
        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email_register, $hashed_password);

        if ($stmt->execute()) {
            // Brukeren er registrert, logg inn brukeren
            $_SESSION['user_email'] = $email_register;
            header("Location: dashboard.php");  // Redirect til dashboard etter vellykket registrering
            exit();
        } else {
            $register_error = "Noe gikk galt. Prøv igjen.";
        }
    }

    // Lukk forbindelsen
    $stmt->close();
    $conn->close();
}

// Håndterer pålogging
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Hent e-post og passord fra skjemaet
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Koble til databasen
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Sjekk om forbindelsen til databasen er vellykket
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Beskytt mot SQL-injeksjon ved å bruke forberedte spørringer
    $sql = "SELECT id, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Sjekk om brukeren finnes
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifiser passordet
        if (password_verify($password, $user['password'])) {
            // Passordet er korrekt, logg inn brukeren
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: ../../index.php");  // Redirect til dashboard etter vellykket pålogging
            exit();
        } else {
            $login_error = "Feil passord.";
        }
    } else {
        $login_error = "Brukeren finnes ikke.";
    }

    // Lukk forbindelsen
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
            <li><a href="Databasenettside/htmlogphp/login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

        </div>
    </header>

    <!-- Login form -->
    <div class="form-container" id="login-form">
        <h2>Logg inn</h2>
        <?php if ($login_error): ?>
            <p style="color: red;"><?php echo $login_error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">E-post</label>
                <input type="email" name="email" id="email" placeholder="Skriv inn e-post" required>
            </div>
            <div class="form-group">
                <label for="password">Passord</label>
                <input type="password" name="password" id="password" placeholder="Skriv inn passord" required>
            </div>
            <button type="submit" name="login" class="btn">Logg inn</button>
        </form>
        <p>Har du ikke en konto? <a href="#" id="switchToRegister">Registrer deg</a></p>
    </div>

    <!-- Registration form -->
    <div class="form-container hidden" id="register-form">
        <h2>Registrer deg</h2>
        <!-- Registreringsskjema kan lages her, for eksempel: -->
        <form method="POST">
            <div class="form-group">
                <label for="email_register">E-post</label>
                <input type="email" name="email_register" id="email_register" placeholder="Skriv inn e-post" required>
            </div>
            <div class="form-group">
                <label for="password_register">Passord</label>
                <input type="password" name="password_register" id="password_register" placeholder="Skriv inn passord" required>
            </div>
            <button type="submit" name="register" class="btn">Registrer deg</button>
        </form>
        <p>Har du allerede en konto? <a href="#" id="switchToLogin">Logg inn</a></p>
    </div>

    


    <script>
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const switchToRegister = document.getElementById('switchToRegister');
        const switchToLogin = document.getElementById('switchToLogin');

        // Bytt til registreringsskjema
        switchToRegister.addEventListener('click', (e) => {
            e.preventDefault();
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
        });

        // Bytt til login skjema
        switchToLogin.addEventListener('click', (e) => {
            e.preventDefault();
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
        });
    </script>

</body>
</html>
