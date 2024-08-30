<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Método: POST<br>";

    // Verificar si los datos están en $_POST
    echo "Datos recibidos:<br>";
    print_r($_POST); // Cambia a var_dump si prefieres

    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $habilidades = isset($_POST['habilidades']) ? $_POST['habilidades'] : null;
    $genero = isset($_POST['genero']) ? $_POST['genero'] : null;
    $rareza = isset($_POST['rareza']) ? $_POST['rareza'] : null;

    if ($id && $nombre && $habilidades && $genero && $rareza) {
        // Prepara la consulta de actualización
        $stmt = $conn->prepare("UPDATE pokemon SET nombre = ?, habilidades = ?, genero = ?, rareza = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nombre, $habilidades, $genero, $rareza, $id);

        if ($stmt->execute()) {
            echo "Pokémon actualizado con éxito.";
            header("Location: index.php"); // Redirigir a la página principal después de la actualización
            exit();
        } else {
            echo "Error al actualizar Pokémon: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else {
    echo "Método no permitido. Use POST para enviar datos.";
}

$conn->close();
?>
