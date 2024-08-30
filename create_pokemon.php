<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

// Asegurarse de que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $habilidades = isset($_POST['habilidades']) ? $_POST['habilidades'] : null;
    $genero = isset($_POST['genero']) ? $_POST['genero'] : null;
    $rareza = isset($_POST['rareza']) ? $_POST['rareza'] : null;

    // Validar que todos los campos estén presentes
    if ($id === null || $nombre === null || $habilidades === null || $genero === null || $rareza === null) {
        echo json_encode(['error' => 'Todos los campos son obligatorios.']);
        http_response_code(400);
        exit();
    }

    // Preparar la consulta SQL para insertar datos
    $stmt = $conn->prepare("INSERT INTO pokemon (id, nombre, habilidades, genero, rareza) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $id, $nombre, $habilidades, $genero, $rareza);

    if ($stmt->execute()) {
        // Si la inserción fue exitosa, devolver una respuesta JSON con los datos del Pokémon
        $response = [
            'id' => $id,
            'nombre' => $nombre,
            'habilidades' => $habilidades,
            'genero' => $genero,
            'rareza' => $rareza
        ];
        echo json_encode($response);
        http_response_code(200); // Cambiado a 200 en lugar de 201
    } else {
        // Si hubo un error al ejecutar la consulta, devolver un error
        echo json_encode(['error' => 'No se pudo crear el Pokémon.']);
        http_response_code(500);
    }

    $stmt->close();
} else {
    // Si la solicitud no es POST, devolver un error
    echo json_encode(['error' => 'Método no permitido. Use POST para enviar datos.']);
    http_response_code(405);
}

$conn->close();

// Redireccionar al index.php
header("Location: index.php");
exit();