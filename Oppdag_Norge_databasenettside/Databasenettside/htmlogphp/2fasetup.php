Selvfølgelig! Her er hele koden med noen små justeringer for å sikre at alt fungerer som det skal:

```php
<?php
session_start();
require_once 'dbconfig.php';
require 'vendor/autoload.php';  // Inkluder PHPMailer, hvis du bruker Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['pending_user_email'])) {
    echo "Ingen e-post er registrert for 2FA.";
    exit();
}

$pending_email = $_SESSION['pending_user_email'];
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Sjekk tilkoblingen
if ($conn->connect_error) {
    die("Tilkoblingsfeil: " . $conn->connect_error);
}

// Generer en tilfeldig 2FA-kode
$two_fa_code = rand(100000, 999999);

// Oppdater 2FA-koden i databasen
$sql = "UPDATE users SET two_fa_code = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $two_fa_code, $pending_email);
$stmt->execute();
$stmt->close();
$conn->close();

// Bruk PHPMailer for å sende e-post
$mail = new PHPMailer(true);

try {
    // Serverinnstillinger
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];  // F.eks. smtp.sendgrid.net
    $mail->SMTPAuth = true;
    $mail->Username = 'apikey';  // Hvis du bruker SendGrid
    $mail->Password = $_ENV['SENDGRID_API_KEY'];  // Din SendGrid API-nøkkel
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $_ENV['SMTP_PORT'];  // F.eks. 587 for SendGrid

    // Mottakere
    $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
    $mail->addAddress($pending_email);  // Send e-posten til den ventende e-posten

    // Innholdet til e-posten
    $mail->isHTML(true);
    $mail->Subject = 'Din 2FA-kode for innlogging';

    // HTML for innholdet i e-posten
    $mail->Body = "
    <html>
    <body style='font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px;'>
        <div style='background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
            <h2 style='color: #333; text-align: center;'>Din 2FA-verifikasjonskode</h2>
            <p style='font-size: 18px; color: #555; text-align: center;'>Hei! Her er din 2FA-kode for å fullføre innloggingen på Oppdag-Norge:</p>
            <div style='font-size: 24px; font-weight: bold; color: #ffffff; background-color: #2D7DC8; padding: 15px; border-radius: 5px; text-align: center;'>$two_fa_code</div>
            <p style='font-size: 18px; color: #555; text-align: center;'>Vennligst skriv inn denne koden på nettsiden for å fullføre prosessen.</p>
            <div style='font-size: 14px; color: #888; margin-top: 20px; text-align: center;'>
                <p>Hvis du ikke har bedt om denne koden, kan du ignorere denne e-posten.</p>
                <p>Besøk oss på <a href='https://www.oppdagnorge.no' style='color: #888; text-decoration: none;'>Oppdag-Norge.no</a></p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Send e-posten
    $mail->send();
    echo "E-posten med 2FA-kode ble sendt!";
} catch (Exception $e) {
    error_log("E-posten kunne ikke sendes. Feil: {$mail->ErrorInfo}");
    echo "E-posten kunne ikke sendes. Feil: {$mail->ErrorInfo}";
}
?>
```

Prøv denne koden og se om det hjelper. Hvis du fortsatt har problemer, kan vi feilsøke videre sammen. 😊