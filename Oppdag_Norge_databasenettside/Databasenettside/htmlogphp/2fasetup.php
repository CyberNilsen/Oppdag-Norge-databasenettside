<?php
session_start();
require_once 'dbconfig.php';

if (!isset($_SESSION['pending_user_email']) || !isset($_SESSION['2fa_code'])) {
    echo "Ingen e-post er registrert for 2FA, eller koden mangler.";
    exit();
}

//Her så sjekker vi om eposten finnes eller om koden finnes hvis ikke den finnes så står det en feil melding og da avslutter eller vi stopper koden.
$pending_email = $_SESSION['pending_user_email'];
$two_fa_code = $_SESSION['2fa_code'];

//Her lagres det variabler/informasjon fra økten lagres.
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sett opp 2FA</title>
</head>
<body>
<h1>Sett opp 2FA</h1>
<p>Her er din 2FA-kode: <?php echo htmlspecialchars($two_fa_code); ?></p>
<form action="2fa_verify.php" method="POST">
    <label for="2fa_code">Bekreft kode:</label>
    <input type="text" name="2fa_code" required>
    <button type="submit">Bekreft</button>
</form>
</body>
</html>

<!-- Her så sendes koden og det er også et input feil og osv. -->