<?php
$servername = "localhost";
$username = "root";
$password = ""; // Si tienes una contraseña, reemplaza esto por la contraseña correspondiente
$database = "apipokemon";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
