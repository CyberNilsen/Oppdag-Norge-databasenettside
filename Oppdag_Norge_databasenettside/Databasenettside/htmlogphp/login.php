<?php
session_start();

$login_error = '';
$register_error = '';
$email_register = ''; 
$email = ''; 

require_once 'dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name_register = $_POST['name_register'];
    $email_register = $_POST['email_register'];
    $password_register = $_POST['password_register'];

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

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
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name_register, $email_register, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['user_email'] = $email_register;
            header("Location: dashboard.php");
            exit();
        } else {
            $register_error = "Noe gikk galt. Prøv igjen.";
        }
    }

    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: ../../index.php");
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
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oppdag Norge - Logg Inn</title>
    <link rel="icon" type="image/x-icon" href="../bilder/OppdagNorge.png">
    <link rel="stylesheet" href="../css/login.css">
    <style>
        .fade-out {
            animation: fadeOut 3s forwards;
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            100% { opacity: 0; display: none; }
        }
    </style>
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

    <div class="form-container" id="login-form">
        <h2>Logg inn</h2>
        <?php if ($login_error): ?>
            <p style="color: red;" id="loginError"><?php echo $login_error; ?></p>
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
            <button type="submit" name="login" class="btn">Logg inn</button>
        </form>
        <p>Har du ikke en konto? <a href="#" id="switchToRegister">Registrer deg</a></p>
    </div>

    <div class="form-container hidden" id="register-form">
        <h2>Registrer deg</h2>
        <?php if ($register_error): ?>
            <p style="color: red;" id="registerError"><?php echo $register_error; ?></p>
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
            <button type="submit" name="register" class="btn">Registrer deg</button>
        </form>
        <p>Har du allerede en konto? <a href="#" id="switchToLogin">Logg inn</a></p>
    </div>

    <script>
        const loginError = document.getElementById('loginError');
        const registerError = document.getElementById('registerError');

        if (loginError) {
            setTimeout(() => loginError.classList.add('fade-out'), 3000);
        }

        if (registerError) {
            setTimeout(() => registerError.classList.add('fade-out'), 3000);
        }

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
