<?php
session_start();
require_once 'dbconfig.php';
require 'vendor/autoload.php';

use PHPGangsta_GoogleAuthenticator;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $method = $_POST['method'];
    $userId = $_SESSION['user_id'];

    if ($method == "google_authenticator") {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();

      
        $stmt = $conn->prepare("UPDATE users SET two_fa_enabled = 1, two_fa_code = ? WHERE id = ?");
        $stmt->bind_param("si", $secret, $userId);
        $stmt->execute();

        $qrCodeUrl = $ga->getQRCodeGoogleUrl('OppdagNorge', $secret);

        echo "<p>Skann denne QR-koden i Google Authenticator-appen:</p>";
        echo "<img src='$qrCodeUrl' alt='QR-kode'>";
        echo "<a href='dashboard.php'>Tilbake til Dashboard</a>";
        exit();
    } elseif ($method == "email") {
       
        $stmt = $conn->prepare("UPDATE users SET two_fa_enabled = 1, two_fa_code = NULL WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        echo "E-post 2FA er aktivert. Koder vil bli sendt ved pålogging.";
        echo "<a href='dashboard.php'>Tilbake til Dashboard</a>";
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktiver 2FA</title>
</head>
<body>
    <h1>Velg to-faktor metode</h1>
    <form method="POST">
        <label>
            <input type="radio" name="method" value="google_authenticator" required> Google Authenticator
        </label>
        <br>
        <label>
            <input type="radio" name="method" value="email" required> E-post
        </label>
        <br>
        <button type="submit">Aktiver</button>
    </form>
</body>
</html>
