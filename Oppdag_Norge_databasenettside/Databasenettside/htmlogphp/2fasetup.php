<?php
session_start();
require_once 'dbconfig.php';

if (!isset($_SESSION['pending_user_email'])) {
    echo "Ingen e-post er registrert for 2FA.";
    exit();
}

$pending_email = $_SESSION['pending_user_email'];
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$two_fa_code = rand(100000, 999999);
$sql = "UPDATE users SET two_fa_code = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $two_fa_code, $pending_email);
$stmt->execute();
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sett opp 2FA</title>
</head>
<body>
<h1>Sett opp 2FA</h1>
<p>Din 2FA-kode: <?php echo $two_fa_code; ?></p>
<form action="2fa_verify.php" method="POST">
    <label for="2fa_code">Bekreft kode:</label>
    <input type="text" name="2fa_code" required>
    <button type="submit">Bekreft</button>
</form>
</body>
</html>
