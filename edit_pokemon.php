<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

// Verificar que el ID del Pokémon se pasa por la URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Asegúrate de que el ID sea un número entero

    // Consultar los datos del Pokémon
    $stmt = $conn->prepare("SELECT * FROM pokemon WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pokemon = $result->fetch_assoc();

    if ($pokemon) {
        // Mostrar el formulario de edición con los datos actuales
?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Pokémon</title>
        </head>

        <body>

            <h1>Editar Pokémon</h1>

            <form action="update_pokemon.php" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($pokemon['id']); ?>">

                <label for="nombre">Nombre:</label><br>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($pokemon['nombre']); ?>" required><br>

                <label for="habilidades">Habilidades:</label><br>
                <input type="text" id="habilidades" name="habilidades" value="<?php echo htmlspecialchars($pokemon['habilidades']); ?>" required><br>

                <label for="genero">Género:</label><br>
                <input type="text" id="genero" name="genero" value="<?php echo htmlspecialchars($pokemon['genero']); ?>" required><br>

                <label for="rareza">Rareza:</label><br>
                <input type="text" id="rareza" name="rareza" value="<?php echo htmlspecialchars($pokemon['rareza']); ?>" required><br><br>

                <input type="submit" value="Actualizar Pokémon">
            </form>


        </body>

        </html>
<?php
    } else {
        echo "Pokémon no encontrado.";
    }

    $stmt->close();
} else {
    echo "ID del Pokémon no proporcionado.";
}

$conn->close();
?>