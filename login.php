<?php
session_start();

// Definir contraseña específica
define('PASSWORD', 'messi'); // Cambia esto por tu contraseña

// Procesar el inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'])) {
    $password = $_POST['password'];

    if ($password === PASSWORD) {
        $_SESSION['loggedin'] = true;
        header('Location: devolver_compus.php'); // Redirigir a la página principal
        exit();
    } else {
        $mensaje = "Contraseña incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #e6f7ff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input {
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
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Inicio de Sesión</h2>
    <?php if (isset($mensaje)): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>
