<?php

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
?>
