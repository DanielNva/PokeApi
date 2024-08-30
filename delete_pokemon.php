<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

// Verifica que el método de solicitud sea DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Obtener datos de la solicitud DELETE
    parse_str(file_get_contents("php://input"), $data);
    $id = isset($data['id']) ? $data['id'] : null;

    // Verifica los datos recibidos
    echo "Datos recibidos: ";
    print_r($data);

    // Asegúrate de que el ID no sea nulo
    if ($id) {
        // Prepara la consulta de eliminación
        $stmt = $conn->prepare("DELETE FROM pokemon WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Pokémon eliminado con éxito."]);
        } else {
            echo json_encode(["error" => "Error al eliminar Pokémon: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "ID del Pokémon es obligatorio."]);
    }
} else {
    echo json_encode(["error" => "Método no permitido. Use DELETE para enviar datos."]);
}

$conn->close();
