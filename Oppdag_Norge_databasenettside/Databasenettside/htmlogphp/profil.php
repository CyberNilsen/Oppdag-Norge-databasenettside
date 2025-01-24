<?php
session_start();

if (!isset($_SESSION['user_email'])) {
 
    header("Location: Databasenettside/htmlogphp/login.php");
    exit();
}
?>
<!-- Her så sjekkes det ovenfor om en bruker er satt og den starter også økten. Hvis brukeren ikke er satt så blir du sendt til login.php -->

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="icon" type="image/x-icon" href="../bilder/OppdagNorgemindre.png">
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
                    <li><a href="../htmlogphp/profil.php">Profil</a></li>
                    <li><a href="../htmlogphp/logut.php">Logg ut</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <h1>Velkommen til dashboardet, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '';?>!</h1>
        <p>Her kan du få oversikt over dine favoritter, planlagte reiser, eller annen relevant informasjon.</p>
    </main>
</body>
</html>

<!-- Ovenfor er det html kode og du ser også velkommen til dashboard + navnet ditt. 