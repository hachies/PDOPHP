<?php
session_start(); // Start de sessie

// Controleer of de gebruiker is ingelogd
if (isset($_SESSION['userId'])) {
    // stop de sessievariabele
    unset($_SESSION['userId']);
    // session_destroy();

    // Stuur de gebruiker door naar de uitlogpagina of een andere pagina
    header("Location: login.php");
    exit();
} else {
    // Als de gebruiker niet is ingelogd, stuur ze dan naar de inlogpagina
    header("Location: login.php");
    exit();
}
?>