<?php
// Configuración de la URL base de la PokeAPI
$baseUrl = "https://pokeapi.co/api/v2/pokemon/";

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto si usas un usuario diferente
$password = ""; // Cambia esto si usas una contraseña diferente
$dbname = "apipokemon";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener y agregar múltiples Pokémon a la base de datos
for ($i = 1; $i <= 10; $i++) { // Cambia el valor de 10 a la cantidad de Pokémon que quieras agregar
    $url = $baseUrl . $i;

    // Inicializar cURL
    $ch = curl_init();
    
    // Configurar opciones de cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Ejecutar la solicitud
    $response = curl_exec($ch);
    
    // Verificar si hay errores
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch) . "<br>";
        continue;
    } else {
        // Decodificar la respuesta JSON a un array asociativo
        $data = json_decode($response, true);
        
        // Extraer los datos necesarios
        $id = $data['id'];
        $nombre = $data['name'];
    
        // Formatear habilidades como una cadena de texto
        $habilidades = array_map(function($ability) {
            return $ability['ability']['name'];
        }, $data['abilities']);
        $habilidades_str = implode(", ", $habilidades);
    
        // Valores fijos para género y rareza
        $genero = "No disponible"; 
        $rareza = "No disponible"; 
    
        // Preparar la consulta SQL para insertar o actualizar
        $sql = "INSERT INTO pokemon (id, nombre, habilidades, genero, rareza) 
                VALUES (?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                nombre = VALUES(nombre), 
                habilidades = VALUES(habilidades), 
                genero = VALUES(genero), 
                rareza = VALUES(rareza)";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $id, $nombre, $habilidades_str, $genero, $rareza);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Pokémon con ID $id agregado o actualizado correctamente en la base de datos.<br>";
        } else {
            echo "Error al insertar o actualizar el Pokémon con ID $id: " . $stmt->error . "<br>";
        }

        // Cerrar la conexión de inserción
        $stmt->close();
    }

    // Cerrar cURL
    curl_close($ch);
}

// Mostrar al menos 10 Pokémon de la base de datos
$sql = "SELECT * FROM pokemon LIMIT 10";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Mostrar los datos de cada Pokémon
    while($row = $result->fetch_assoc()) {
        echo "<br>ID: " . $row["id"] . "<br>";
        echo "Nombre: " . $row["nombre"] . "<br>";
        echo "Habilidades: " . $row["habilidades"] . "<br>";
        echo "Género: " . $row["genero"] . "<br>";
        echo "Rareza: " . $row["rareza"] . "<br><br>";
    }
} else {
    echo "No se encontraron Pokémon en la base de datos.";
}

// Cerrar la conexión
$conn->close();

// Redireccionar al index.php
header("Location: index.php");
exit();