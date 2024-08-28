<?php
// Establecer la zona horaria en Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Definir datos de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registro_compus";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['docente'])) {
    $docente = $conn->real_escape_string($_POST['docente']);
    $curso = $conn->real_escape_string($_POST['curso']);
    $compus = isset($_POST['compus']) ? $_POST['compus'] : ''; // Asegúrate de que 'compus' esté definido
    $hora_ingreso = date('Y-m-d H:i:s'); // Hora y fecha actual en la zona horaria de Argentina
    $estado = $conn->real_escape_string($_POST['estado']);
    $observaciones = isset($_POST['observaciones']) ? $conn->real_escape_string($_POST['observaciones']) : ''; // Verificar que existan observaciones

    // Convertir los códigos de computadoras en un array y eliminar espacios
    $compus_array = array_map('trim', explode("\n", $compus));
    // Eliminar entradas vacías y duplicadas
    $compus_array = array_filter(array_unique($compus_array));

    // Verificar duplicados y construir el string de computadoras
    $compus_validas = [];
    foreach ($compus_array as $compu) {
        $compu = $conn->real_escape_string($compu);
        $check_sql = "SELECT * FROM registros WHERE compus LIKE '%$compu%' AND estado='Prestada'";
        $result = $conn->query($check_sql);

        if ($result->num_rows == 0) {
            $compus_validas[] = $compu;
        } else {
            $mensaje .= "Error: La computadora $compu ya está registrada como prestada.<br>";
        }
    }

    // Si hay computadoras válidas, guardarlas
    if (!empty($compus_validas)) {
        $compus_string = implode(", ", $compus_validas);
        $sql = "INSERT INTO registros (docente, curso, compus, hora_ingreso, estado, observaciones)
                VALUES ('$docente', '$curso', '$compus_string', '$hora_ingreso', '$estado', '$observaciones')";

        if ($conn->query($sql) === TRUE) {
            $mensaje = "Registro agregado exitosamente con las siguientes computadoras: $compus_string<br>";
            // Redirigir a la misma página después de 1 segundo para evitar doble envío
            echo '<script>
                    setTimeout(function() {
                        window.location.href = window.location.href;
                    }, 1000);
                  </script>';
        } else {
            $mensaje .= "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $mensaje .= "No se encontraron computadoras válidas para agregar.<br>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Computadoras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #4682b4;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #e6f7ff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4682b4;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4169e1;
        }
        .mensaje {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Registro de Computadoras</h1>

<?php if (!empty($mensaje)): ?>
    <div class="mensaje">
        <?php echo $mensaje; ?>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <label for="docente">Docente:</label>
    <input type="text" id="docente" name="docente" required>

    <label for="curso">Curso:</label>
    <select id="curso" name="curso" required>
        <option value="">Selecciona un curso</option>
        <?php
        // Generar las opciones de cursos
        for ($i = 1; $i <= 7; $i++) {
            echo "<option value=\"$i-A\">$i-A</option>";
            echo "<option value=\"$i-B\">$i-B</option>";
        }
        ?>
    </select>

    <label for="compus">Códigos de Computadoras (una por línea):</label>
    <textarea id="compus" name="compus" rows="5" required placeholder="Ingresa cada código en una nueva línea"></textarea>

    <input type="hidden" id="hora_ingreso" name="hora_ingreso">

    <label for="estado">Estado:</label>
    <select id="estado" name="estado" required>
        <option value="Prestada">Prestada</option>
    </select>

    <label for="observaciones">Observaciones:</label>
    <textarea id="observaciones" name="observaciones" rows="3" placeholder="Escribe tus observaciones aquí"></textarea>

    <button type="submit">Guardar</button>
</form>

</body>
</html>
