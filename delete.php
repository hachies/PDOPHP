<?php
include 'db.php';  
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['gebruiker_id'];

    $db->deleteData($id);

    header("Location: select.php");  // Vervang gebruikers_overzicht.php met de juiste bestemming
    exit();

} else {
    $id = $_GET['gebruiker_id'];

    $result = $db->selectData($id);

    if (!empty($result)) {
        $data = $result[0]; 
        ?>
        <form method="post" action="">
            <input type="hidden" name="gebruiker_id" value="<?php echo $data['gebruiker_id']; ?>">
            Weet u zeker dat u deze gebruiker wilt verwijderen?
            <input type="submit" value="Ja">
        </form>
        <?php
    } else {
        echo "Gebruiker niet gevonden.";
    }
}
?>
