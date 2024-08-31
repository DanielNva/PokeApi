<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

// Verificar que el método de solicitud sea POST y que se indique PUT en _method
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    // Obtener los datos de la solicitud POST
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $habilidades = isset($_POST['habilidades']) ? $_POST['habilidades'] : null;
    $genero = isset($_POST['genero']) ? $_POST['genero'] : null;
    $rareza = isset($_POST['rareza']) ? $_POST['rareza'] : null;

    // Verifica que todos los campos estén presentes
    if ($id && $nombre && $habilidades && $genero && $rareza) {
        // Prepara la consulta de actualización
        $stmt = $conn->prepare("UPDATE pokemon SET nombre = ?, habilidades = ?, genero = ?, rareza = ? WHERE id = ?");
        $stmt->bind_param("sssii", $nombre, $habilidades, $genero, $rareza, $id);

        if ($stmt->execute()) {
            echo json_encode([
                "message" => "Pokémon actualizado con éxito.",
                "id" => $id,
                "nombre" => $nombre,
                "habilidades" => $habilidades,
                "genero" => $genero,
                "rareza" => $rareza
            ]);
            http_response_code(200);
        } else {
            echo json_encode(["error" => "Error al actualizar Pokémon: " . $stmt->error]);
            http_response_code(500); // Indicar que hubo un error en el servidor
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Todos los campos son obligatorios."]);
        http_response_code(400); // Indicar que la solicitud fue incorrecta
    }
} else {
    echo json_encode(["error" => "Método no permitido. Use POST con _method=PUT para enviar datos."]);
    http_response_code(405); // Indicar que el método no está permitido
}

$conn->close();
?>
