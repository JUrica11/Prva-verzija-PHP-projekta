<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "moj_projekt";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veza nije uspjela: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM korisnici WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("Korisnik ne postoji.");
    }
} else {
    die("ID nije proslijeđen.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = htmlspecialchars($_POST["ime"]);
    $prezime = htmlspecialchars($_POST["prezime"]);
    $email = htmlspecialchars($_POST["email"]);

    $sql = "UPDATE korisnici SET ime=?, prezime=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $ime, $prezime, $email, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Podaci su uspješno ažurirani!";
        header("Location: index.php");
        exit();
    } else {
        echo "Greška: " . $stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Uredi korisnika</title>
</head>
<body>
    <h2>Uredi korisnika</h2>
    <form method="POST">
        <input type="text" name="ime" value="<?= htmlspecialchars($user['ime']) ?>" required>
        <input type="text" name="prezime" value="<?= htmlspecialchars($user['prezime']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <input type="submit" value="Spremi promjene">
    </form>
</body>
</html>
