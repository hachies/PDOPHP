<?php
    // zo connect je met de db.php zodat je de hele code niet hoeft te herhalen
    include 'db.php';
    // nieuwe database
    $database = new Database();

    // Controleert of je daadwerkelijk verbinding hebt gemaakt met de database
    if ($database->pdo) {
        echo "you're in!";
    } else {
        echo "failed connection, try again!.";
    }
?>
