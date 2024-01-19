<?php
include 'db.php'; 

$error_message = '';  

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {  // Controleer of het formulier is verzonden met POST-methode
        $email = htmlspecialchars($_POST['email']);  // Ontvang en schoon de ingevoerde e-mail
        $db = new Database();  // Maak een nieuw databaseobject aan
        $user = $db->login($email);  // Roep de loginfunctie op om gebruikersinformatie op te halen

        if ($user) {  // Als de gebruiker bestaat
            $wachtwoord = $_POST['wachtwoord'];  // Ontvang het ingevoerde wachtwoord
            $verify = password_verify($wachtwoord, $user['wachtwoord']);  // Controleer of het ingevoerde wachtwoord overeenkomt met de gehashte versie in de database

            if ($verify) {  // Als het wachtwoord correct is
                session_start();  // Start de sessie
                $_SESSION['userId'] = $user['id'];  // Sla de gebruikersinformatie op in de sessievariabelen
                $_SESSION['naam'] = $user['naam'];
                $_SESSION['role'] = $user['admin'];
                header('Location: home.php?ingelogd');  // Stuur de gebruiker door naar de homepagina
            } else {
                $error_message = "Verkeerd wachtwoord";  // Als het wachtwoord onjuist is, stel het foutbericht in
            }
        } else {
            $error_message = "Verkeerde email of wachtwoord";  // Als de gebruiker niet bestaat, stel het foutbericht in
        }
    }
} catch (\Exception $e) {
    $error_message = $e->getMessage();  // Vang eventuele uitzonderingen op en stel het foutbericht in
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Login</title>  
    <link rel="stylesheet" href="login.css"> 
</head>
<body>
    <div class="container">
        <form class="login-form" action="" method="post">
            <h2>Login</h2>  
            <div class="box1"><br> 
                <label for="email">Email-adres</label><br> 
                <input type="email" name="email" placeholder="xxxx@example.com" required><br>
                <label for="password">Wachtwoord</label><br>
                <input type="password" name="wachtwoord" placeholder="*******" required><br>
                <button type="submit">Inloggen</button><br>
            </div>
            <p>Nog geen account? <a href="register.php">Maak een nieuw account aan</a></p>
        </form>  
    </body>
</div>
</html>
<?php echo '<p style="color: red;">' . $error_message . '</p>'; ?>


