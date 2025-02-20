<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "moj_projekt";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Veza nije uspjela: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM korisnici WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Korisnik je uspješno obrisan.";
    } else {
        echo "Greška: " . $stmt->error;
    }
}

header("Location: index.php");
exit();
