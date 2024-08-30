<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Método: " . $_SERVER['REQUEST_METHOD'] . "<br>";

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    echo "Datos recibidos:<br>";
    print_r($_POST);
} else {
    echo "Método no permitido. Use POST para enviar datos.";
}
?>
