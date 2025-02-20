<?php
session_start();
require_once "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = htmlspecialchars($_POST["ime"]);
    $prezime = htmlspecialchars($_POST["prezime"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashiramo lozinku

    $stmt = $conn->prepare("INSERT INTO korisnici (ime, prezime, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $ime, $prezime, $email, $password);

    if ($stmt->execute()) {
        echo "Registracija uspješna!";
    } else {
        echo "Greška: " . $stmt->error;
    }
}
?>
