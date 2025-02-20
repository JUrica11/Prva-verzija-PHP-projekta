<?php
session_start();
require_once "config.php"; // Uključi fajl u kojem je konekcija na bazu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM korisnici WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["email"] = $email;
            echo "Prijava uspješna!"; 
            header("Location: index.php"); // Preusmjeri korisnika
            exit();
        } else {
            echo "Pogrešna lozinka!";
        }
    } else {
        echo "Nema korisnika s tim emailom!";
    }
}
?>
