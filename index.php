<?php
include 'db_connection.php';

// Consulta para obtener todos los Pokémon de la base de datos
$sql = "SELECT id, nombre, habilidades, genero, rareza FROM pokemon";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Lista de Pokémon</title>
</head>

<body>

    <h1>Lista de Pokémon</h1>

    <h2>Base de Datos de Pokémon</h2>

    <form action="read_pokemon.php" method="POST">
        <input type="submit" value="Cargar Pokémon desde la API">
    </form>

    <h2>Agregar nuevo Pokémon</h2>
    <form action="create_pokemon.php" method="POST">
        <label for="id">ID:</label><br>
        <input type="number" id="id" name="id" required><br>

        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="habilidades">Habilidades:</label><br>
        <input type="text" id="habilidades" name="habilidades" required><br>

        <label for="genero">Género:</label><br>
        <input type="text" id="genero" name="genero" required><br>

        <label for="rareza">Rareza:</label><br>
        <input type="text" id="rareza" name="rareza" required><br><br>

        <input type="submit" value="Agregar Pokémon">
    </form>
    <form action="delete_pokemon.php" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        <label for="id">ID del Pokémon a eliminar:</label>
        <input type="number" id="id" name="id" required>
        <input type="submit" value="Eliminar">
    </form>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Habilidades</th>
                <th>Género</th>
                <th>Rareza</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo !empty($row['id']) ? htmlspecialchars($row['id']) : 'No disponible'; ?></td>
                    <td><?php echo !empty($row['nombre']) ? htmlspecialchars($row['nombre']) : 'No disponible'; ?></td>
                    <td><?php echo !empty($row['habilidades']) ? htmlspecialchars($row['habilidades']) : 'No disponible'; ?></td>
                    <td><?php echo !empty($row['genero']) ? htmlspecialchars($row['genero']) : 'No disponible'; ?></td>
                    <td><?php echo !empty($row['rareza']) ? htmlspecialchars($row['rareza']) : 'No disponible'; ?></td>
                    <td>
                        <a href="edit_pokemon.php?id=<?php echo urlencode($row['id']); ?>">Editar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No hay Pokémon en la base de datos.</p>
    <?php endif; ?>

</body>

</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>