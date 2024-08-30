<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

// Verifica que el método de solicitud sea POST y que se indique DELETE en _method
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
    // Obtener el ID desde los datos POST
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    // Depurar para verificar el ID recibido
    echo "ID recibido: " . $id . "\n"; // Muestra el ID recibido en la salida

    // Registrar el ID en el archivo de log
    error_log("ID recibido: " . $id);

    // Verifica que el ID no sea nulo
    if ($id !== null && $id > 0) {
        // Prepara la consulta de eliminación
        $stmt = $conn->prepare("DELETE FROM pokemon WHERE id = ?");
        if (!$stmt) {
            echo json_encode(["error" => "Error al preparar la consulta: " . $conn->error]);
            error_log("Error al preparar la consulta: " . $conn->error);
            http_response_code(500); // Indicar que hubo un error en el servidor
            exit();
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode([
                "message" => "Pokémon eliminado con éxito.",
                "id" => $id
            ]);
            http_response_code(200); // Código de éxito
        } else {
            echo json_encode(["error" => "Error al eliminar Pokémon: " . $stmt->error]);
            error_log("Error al eliminar Pokémon: " . $stmt->error);
            http_response_code(500); // Indicar que hubo un error en el servidor
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "ID del Pokémon es obligatorio y debe ser mayor a 0."]);
        http_response_code(400); // Indicar que la solicitud fue incorrecta
    }
} else {
    echo json_encode(["error" => "Método no permitido. Use POST con _method=DELETE para enviar datos."]);
    http_response_code(405); // Indicar que el método no está permitido
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
