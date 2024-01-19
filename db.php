<?php

class Database {
    public $pdo;
    private string $userTable = "user";

    public function __construct(String $db = "rentacar", String $host = "localhost", String $user = "root", String $pass = "") {
        try {
            // Verbinding met de database tot stand brengen
            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Foutafhandeling als de verbinding mislukt
            die("Connection failed: " . $e->getMessage());
        }
    }

    // User management methods
    public function insertUser(string $email, string $password): string | false {
        try {
            // Voorbereiden en uitvoeren van een SQL-invoegquery voor een nieuwe gebruiker
            $stmt = $this->pdo->prepare("INSERT INTO $this->userTable (email, password) VALUES (?, ?)");
            $stmt->execute([$email, $password]);
            return $this->pdo->lastInsertId(); // Teruggeven van het gegenereerde gebruikers-ID
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
            return false;
        }
    }

    public function selectAllUsers(): array {
        try {
            // Voorbereiden en uitvoeren van een SQL-query om alle gebruikers op te halen
            $stmt = $this->pdo->query("SELECT * FROM $this->userTable");
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Teruggeven van resultaten als associatieve array
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
            return [];
        }
    }

    public function selectOneUser(int $id): array {
        try {
            // Voorbereiden en uitvoeren van een SQL-query om één gebruiker op te halen op basis van ID
            $stmt = $this->pdo->prepare("SELECT * FROM $this->userTable WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC); // Teruggeven van resultaten als associatieve array
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
            return [];
        }
    }

    public function updateUser(string $email, string $password, int $id) {
        try {
            // Voorbereiden en uitvoeren van een SQL-query om gebruikersgegevens bij te werken op basis van ID
            $stmt = $this->pdo->prepare("UPDATE $this->userTable SET email = ?, password = ? WHERE id = ?");
            $stmt->execute([$email, $password, $id]);
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
        }
    }

    public function deleteUser(int $id) {
        try {
            // Voorbereiden en uitvoeren van een SQL-query om een gebruiker te verwijderen op basis van ID
            $stmt = $this->pdo->prepare("DELETE FROM $this->userTable WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
        }
    }

    // Car management methods
    public function selectCars(bool $availableOnly = true): array {
        try {
            // Voorbereiden en uitvoeren van een SQL-query om auto's op te halen (met optionele filter voor beschikbaarheid)
            if ($availableOnly) {
                $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE beschikbaarheid = 1");
            } else {
                $stmt = $this->pdo->prepare("SELECT * FROM cars");
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Teruggeven van resultaten als associatieve array
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
            return [];
        }
    }

    public function insertCar($merk, $model, $jaar, $kenteken, $beschikbaarheid, $prijs, $foto_naam) {
        try {
            // Voorbereiden en uitvoeren van een SQL-invoegquery voor een nieuwe auto
            $query = "INSERT INTO cars (merk, model, jaar, kenteken, beschikbaarheid, prijs, foto_naam) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$merk, $model, $jaar, $kenteken, $beschikbaarheid, $prijs, $foto_naam]);
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
        }
    }

    // Reservation methods
    public function calculateTotalAmount($auto_id, $start_date, $end_date) {
        try {
            // Berekenen van het totale bedrag op basis van de opgegeven auto, startdatum en einddatum
            $stmt = $this->pdo->prepare("SELECT prijs FROM cars WHERE auto_id = ?");
            $stmt->execute([$auto_id]);
            $car = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$car) {
                return false;
            }

            $daily_price = $car['prijs'];
            $start_datetime = new DateTime($start_date);
            $end_datetime = new DateTime($end_date);
            $interval = $start_datetime->diff($end_datetime);
            $total_days = $interval->days + 1;  // Add 1 to include the start day
            $total_amount = $total_days * $daily_price;

            return $total_amount;
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
            return false;
        }
    }

    public function aanmelden($naam, $achternaam, $geboortedatum, $email, $password) {
        // Een nieuwe gebruiker aanmelden
        $stmt = $this->pdo->prepare("INSERT INTO users (naam,achternaam,geboortedatum,email,wachtwoord) values (?,?,?,?,?)");
        $stmt->execute([$naam, $achternaam, $geboortedatum, $email, $password]);
    }

    public function createReservation($auto_id, $start_date, $end_date, $total_amount) {
        try {
            // Een nieuwe reservering maken en toevoegen aan de database
            $stmt = $this->pdo->prepare("INSERT INTO reservationss (auto_id, start_datum, eind_datum, totaal_bedrag, aangemaakt_op) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
            $stmt->execute([$auto_id, $start_date, $end_date, $total_amount]);
            return $this->pdo->lastInsertId(); // Teruggeven van het gegenereerde reserverings-ID
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
            return false;
        }
    }

    public function getCarDetailsById($auto_id) {
        try {
            // Ophalen van details van een auto op basis van ID
            $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE auto_id = ?");
            $stmt->execute([$auto_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC); // Teruggeven van resultaten als associatieve array
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
            return false;
        }
    }

    public function login($email) {
        // Inloggen met behulp van e-mailadres
        $stmt = $this->pdo->prepare("SELECT * FROM users where email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result;
    }

    // Voeg deze methode toe aan je Database-klasse
    public function selectAllReservations(): array {
        try {
            // Ophalen van alle reserveringen uit de database
            $stmt = $this->pdo->query("SELECT * FROM reservationss");
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Teruggeven van resultaten als associatieve array
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
            return [];
        }
    }

    public function getReservationsByUserId($user_id): array {
        try {
            // Ophalen van reserveringen op basis van gebruikers-ID
            $stmt = $this->pdo->prepare("SELECT * FROM reservationss WHERE user_id = ?");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Teruggeven van resultaten als associatieve array
        } catch (PDOException $e) {
            // Foutafhandeling of logging in geval van een fout
            return [];
        }
    }
}

?>
