<?php
session_start();

session_unset();


session_destroy();


header("Location: ../../index.php");
exit();

//Her så starter den først økten, så fjerner den alle variabler og alt som er lagret hittil, deretter så stopper den php økten og så bli du videre sendt til hoved siden.

?>