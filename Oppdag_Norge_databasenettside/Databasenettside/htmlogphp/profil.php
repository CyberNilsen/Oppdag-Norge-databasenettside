<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    // Hvis brukeren ikke er logget inn, omdiriger til login-siden
    header("Location: Databasenettside/htmlogphp/login.php");
    exit();
}
?>

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
        <h1>Velkommen til dashboardet, <?php echo htmlspecialchars($_SESSION['user_email']); ?>!</h1>
        <p>Her kan du få oversikt over dine favoritter, planlagte reiser, eller annen relevant informasjon.</p>
    </main>
</body>
</html>
