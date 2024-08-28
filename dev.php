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

// Eliminar un registro si se hace clic en el botón de eliminar
if (isset($_POST['eliminar'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $sql = "DELETE FROM registros WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Registro eliminado exitosamente";
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }
}

// Consultar los registros de computadoras
$sql = "SELECT id, docente, curso, compus, hora_ingreso, estado, observaciones FROM registros WHERE estado='Prestada'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Computadoras</title>
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
            background-color: #ff6347;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff4500;
        }
    </style>
</head>
<body>

<h1>Listado de Computadoras</h1>

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
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>";
    // Mostrar registros en la tabla
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["docente"] . "</td>
                <td>" . $row["curso"] . "</td>
                <td>" . $row["compus"] . "</td>
                <td>" . $row["hora_ingreso"] . "</td>
                <td>" . $row["estado"] . "</td>
                <td>" . $row["observaciones"] . "</td>
                <td>
                    <form method='POST' action=''>
                        <input type='hidden' name='id' value='" . $row["id"] . "'>
                        <button type='submit' name='eliminar'>Eliminar</button>
                    </form>
                </td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron registros.";
}

$conn->close();
?>

</body>
</html>
