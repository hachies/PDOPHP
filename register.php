<?php
include 'db.php'; // Importeer de database connectie

$error_message = ""; // Initialiseer een lege string voor foutmeldingen

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Controleer of het een POST-verzoek is
        if (isset($_POST['naam']) && isset($_POST['achternaam']) && isset($_POST['geboortedatum']) && isset($_POST['email']) && isset($_POST['wachtwoord'])) {
            // Controleer of alle vereiste POST-variabelen zijn ingesteld

            $db = new Database(); // Maak een nieuw database object aan
            $hash = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT); // Genereer een gehasht wachtwoord
            $db->aanmelden($_POST['naam'], $_POST['achternaam'], $_POST['geboortedatum'], $_POST['email'], $hash);
            // Roep de aanmelden methode aan op het database object om een nieuwe gebruiker aan te maken
            header("Location: login.php?accountAangemaakt"); // Stuur de gebruiker door naar de login-pagina met een succesmelding
        } else {
            $error_message = "Niet alle vereiste velden zijn ingevuld."; // Wijs een foutmelding toe als niet alle vereiste velden zijn ingevuld
        }
    }
} catch (\Exception $e) {
    $error_message = "Error: " . $e->getMessage(); // Vang eventuele fouten op en wijs een foutmelding toe
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="register.css">
    <title>Meld hier aan</title>
</head>

<body>
    <div class="container">
        <?php
        if (!empty($error_message)) {
            echo '<p style="color: red;">' . $error_message . '</p>';
        }
        ?>
        <form class="registration-form" action="" method="post">
            <h2>Maak een nieuw account aan</h2>
            <div class="box1"><br>
                <label for="name">Voornaam</label><br>
                <input type="name" name="naam" placeholder="voornaam" required><br>
                <label for="name">Achternaam</label><br>
                <input type="name" name="achternaam" placeholder="achternaam" required><br>
                <label for="date">geboortedatum</label><br>
                <input type="date" name="geboortedatum" required><br>
                <label for="email">Email-adress </label><br>
                <input type="email" name="email" placeholder="xxxx@example.com" required><br>
                <label for="password">Wachtwoord</label><br>
                <input type="password" name="wachtwoord" placeholder="*******" required><br>
                <button type="submit">Aanmelden</button><br>
            </div>
            <p>Heb je al een account? <a href="login.php">log dan hier in!</a></p>
        </form>
    </div>
</body>
</html>
