<?php
session_start();

// Fjern alle øktvariabler
session_unset();

// Ødelegg økten
session_destroy();

// Omdiriger til hjemmesiden
header("Location: ../../index.php");
exit();
?>
