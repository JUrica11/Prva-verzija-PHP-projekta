
    <?php
   session_start();
   require_once "config.php";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "moj_projekt";

    // Povezivanje s bazom
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Provjera veze
    if (!$conn) {
        die("Veza nije uspjela: " . mysqli_connect_error());
    }

    // Umetanje podataka
    if  ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ime = htmlspecialchars($_POST["ime"]);
        $prezime = isset($_POST["prezime"]) ? htmlspecialchars($_POST["prezime"]) : '';
        $email = htmlspecialchars($_POST["email"]);

     
        //Validacija email adrese
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo "Neispravna email adresa.";
        }else{
            //Pripremljena izjava za umetanje 
            $stmt = $conn->prepare("INSERT INTO korisnici (ime, prezime, email) VALUES (?, ?, ?)");
            if (!$stmt) {
                echo "Greška u pripremi izjave: " . $conn->error;
                exit();
            }
            
            $stmt->bind_param("sss", $ime, $prezime, $email);
    
            if ($stmt->execute()) {
                $_SESSION['message'] = "Novi korisnik je uspješno dodan."; // Spremanje poruke u sesiju
                header("Location: index.php"); // Preusmjeri na istu stranicu
                exit();
            } else {
                echo "Greška: " . $stmt->error;
            }
    
            $stmt->close();
        }
    }

    // Prikazivanje poruka
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

$sql = "SELECT * FROM korisnici";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h2>Popis korisnika:</h2>";
    echo "<table border='1'>
            <tr>
                <th>Ime</th>
                <th>Prezime</th>
                <th>Email</th>
                <th>Akcija</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row["ime"] . "</td>
                <td>" . $row["prezime"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>
                    <a href='edit.php?id=" . $row["id"] . "'>Uredi</a> | 
                    <a href='delete.php?id=" . $row["id"] . "' onclick=\"return confirm('Jeste li sigurni da želite obrisati ovog korisnika?');\">Obriši</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Nema korisnika.";
}

mysqli_close($conn);
?>
    
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Moja Web Aplikacija</title>
</head>
<body>
    <h1>Unos korisnika</h1>
    <form method="POST" action="login.php">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Lozinka" required>
    <input type="submit" value="Prijava">
</form>

    <input type="submit" value="Dodaj korisnika">
</form>

</body>
</html>

