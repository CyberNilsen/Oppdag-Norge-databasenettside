<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', '');  
define('DB_NAME', 'oppdagnorge'); 

// Her så definerer vi konstantene eller vi lager konstantene slik at php koden skjønner hva som er hva. Konstantene i dette tilfelle brukes for å koble til databasen.

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

//Her så kobler vi til databasen. 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!-- Hvis du ikke klarer å koble deg til så står det feil melding/er. -->

<!-- Alt i alt dette er konfigurasjons filen til php -->