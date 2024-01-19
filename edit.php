<?php
include 'db.php';

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruiker_id = $_POST['gebruiker_id']; 

    $naam = $_POST['naam'];
    $achternaam = $_POST['achternaam'];
    $leeftijd = $_POST['leeftijd'];

    $db->updateData($gebruiker_id, $naam, $achternaam, $leeftijd);

    header("Location: select.php"); 
    exit();
}

$gebruiker_id = $_GET['gebruiker_id'];

$result = $db->selectData($gebruiker_id);

if (!empty($result)) {
    $data = $result[0];
    ?>
    <form method="post" action="">
        <input type="hidden" name="gebruiker_id" value="<?php echo $data['gebruiker_id']; ?>">
        Naam: <input type="text" name="naam" value="<?php echo $data['naam']; ?>"><br>
        Achternaam: <input type="text" name="achternaam" value="<?php echo $data['achternaam']; ?>"><br>
        Leeftijd: <input type="text" name="leeftijd" value="<?php echo $data['leeftijd']; ?>"><br>
        <input type="submit" value="Update">
    </form>
    <?php
} else {
    echo "Gebruiker niet gevonden.";
}
?>
