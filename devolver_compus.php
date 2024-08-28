<?php
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

// Procesar la devolución de una computadora
if (isset($_POST['devolver'])) {
    $ids = $_POST['ids']; // Array de IDs de computadoras a devolver
    $hora_devolucion = date("Y-m-d H:i:s");

    foreach ($ids as $id) {
        $id = $conn->real_escape_string($id);

        // Actualizar el estado de la computadora
        $sql = "UPDATE registros SET estado='Devuelta', hora_ingreso='$hora_devolucion' WHERE id=$id";

        if ($conn->query($sql) !== TRUE) {
            echo "Error al devolver la computadora con ID $id: " . $conn->error . "<br>";
        }
    }

    // Redirigir a login.php después de devolver las computadoras
    header("Location: login.php");
    exit();
}

// Consultar computadoras prestadas
$sql = "SELECT id, docente, curso, compus, hora_ingreso, estado FROM registros WHERE estado='Prestada'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devolver Computadoras</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4682b4;
            color: white;
        }
        button {
            background-color: #32cd32;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #228b22;
        }
    </style>
</head>
<body>

<h1>Devolver Computadoras</h1>

<form method="POST" action="">
    <?php
    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Docente</th>
                    <th>Curso</th>
                    <th>Computadora</th>
                    <th>Hora de Ingreso</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>";
        // Mostrar registros en la tabla
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["docente"] . "</td>
                    <td>" . $row["curso"] . "</td>
                    <td>" . $row["compus"] . "</td>
                    <td>" . $row["hora_ingreso"] . "</td>
                    <td>" . $row["estado"] . "</td>
                    <td>
                        <input type='checkbox' name='ids[]' value='" . $row["id"] . "'>
                    </td>
                </tr>";
        }
        echo "</table>
              <button type='submit' name='devolver'>Devolver seleccionadas</button>";
    } else {
        echo "No hay computadoras prestadas para devolver.";
    }
    ?>
</form>

</body>
</html>
