<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

// Verifica que el método de solicitud sea POST y que se indique DELETE en _method
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
    // Obtener el ID desde los datos POST
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    // Verifica que el ID no sea nulo
    if ($id) {
        // Prepara la consulta de eliminación
        $stmt = $conn->prepare("DELETE FROM pokemon WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Devolver solo un objeto JSON sin mensajes extra
            echo json_encode([
                "message" => "Pokémon eliminado con éxito.",
                "id" => $id
            ]);
            http_response_code(200); // Código de respuesta 200 para éxito
        } else {
            echo json_encode(["error" => "Error al eliminar Pokémon: " . $stmt->error]);
            http_response_code(500); // Indicar que hubo un error en el servidor
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "ID del Pokémon es obligatorio."]);
        http_response_code(400); // Indicar que la solicitud fue incorrecta
    }
} else {
    echo json_encode(["error" => "Método no permitido. Use POST con _method=DELETE para enviar datos."]);
    http_response_code(405); // Indicar que el método no está permitido
}

$conn->close();

// Redireccionar al index.php
header("Location: index.php");
exit();