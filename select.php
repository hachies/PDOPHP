<?php
class Database {
    public $pdo;

    public function __construct($db = "test", $user="root", $pwd="", $host="localhost"){
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected to database $db";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function selectData($id = null) {
        try {
            if ($id !== null) {
                // SELECT-query met id
                $stmt = $this->pdo->prepare("SELECT * FROM gebruikers WHERE id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            } else {
                // SELECT-query zonder id
                $stmt = $this->pdo->prepare("SELECT * FROM gebruikers");
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Tabel weergeven
            echo '<table border="1">
                    <tr>
                        <th>Gebruiker_id</th>
                        <th>Naam</th>
                        <th>Achternaam</th>
                        <th>Leeftijd</th>
                    </tr>';

            foreach ($result as $row) {
                echo '<tr>
                        <td>'.$row['gebruiker_id'].'</td>
                        <td>'.$row['naam'].'</td>
                        <td>'.$row['achternaam'].'</td>
                        <td>'.$row['leeftijd'].'</td>
                        <td>
                            <a href="edit.php?id='.$row['gebruiker_id'].'">Edit</a>
                            <a href="delete.php?id='.$row['gebruiker_id'].'">Delete</a>
                        </td>
                      </tr>';
            }

            echo '</table>';

            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

$db = new Database();
$db->selectData();
?>
