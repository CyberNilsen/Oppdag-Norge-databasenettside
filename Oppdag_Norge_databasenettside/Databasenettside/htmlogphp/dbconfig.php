<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');  // Vanligvis 'root' for lokal utvikling
define('DB_PASSWORD', '');  // Hvis du har passord på MySQL, legg det inn her
define('DB_NAME', 'oppdagnorge');  // Navnet på databasen din

// Opprett en ny databaseforbindelse
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Sjekk forbindelsen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
