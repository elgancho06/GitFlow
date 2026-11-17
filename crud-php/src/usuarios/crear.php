<?php
// Inicializamos las variables para los mensajes y valores.
$mensaje = "";
$esError = false;
$nombre = "";
$email = "";

// Comprobamos si el formulario se ha enviado mediante POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Comprobamos que se hayan recibido los campos nombre y email.
    if (isset($_POST["nombre"]) && isset($_POST["email"])) {

        // Recogemos los datos enviados desde el formulario.
        $nombre = trim($_POST["nombre"]);
        $email = trim($_POST["email"]);

        // Comprobamos que los campos no estén vacíos.
        if ($nombre === "" || $email === "") {
            $mensaje = "Debes rellenar todos los campos.";
            $esError = true;

            // Comprobamos que el correo electrónico sea válido.
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "El correo electrónico no es válido.";
            $esError = true;

        } else {
            // Incluimos la clase de conexión a MariaDB.
            require_once __DIR__ . "/../config/DatabaseService.php";

            // Creamos un objeto de la clase DatabaseService.
            $databaseService = new DatabaseService();

            try {
                // Obtenemos la conexión PDO con MariaDB.
                $connection = $databaseService->getConnection();

                // Preparamos la consulta SQL para insertar el cliente.
                $query = "INSERT INTO clientes (nombre, email) VALUES (:nombre, :email)";
                $stmt = $connection->prepare($query);

                // Asociamos los parámetros.
                $stmt->bindParam(":nombre", $nombre);
                $stmt->bindParam(":email", $email);

                // Ejecutamos la consulta SQL en MariaDB.
                $resultadoEjecucion = $stmt->execute();

                if ($resultadoEjecucion) {
                    $mensaje = "Cliente insertado correctamente.";
                    $esError = false;
                    // Limpiamos los campos tras el éxito si se desea
                    $nombre = "";
                    $email = "";
                } else {
                    $mensaje = "No se ha podido insertar el cliente.";
                    $esError = true;
                }

            } catch (PDOException $e) {
                $mensaje = "Se ha producido un error al guardar el cliente.";
                $esError = true;
            }
        }
    } else {
        $mensaje = "No has enviado la información suficiente.";
        $esError = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cliente</title>
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-color: #1f2937;
            --border-color: #d1d5db;
            --error-bg: #fee2e2;
            --error-text: #991b1b;
            --error-border: #f87171;
            --success-bg: #dcfce7;
            --success-text: #166534;
            --success-border: #4ade80;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: var(--card-bg);
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        h1 {
            font-size: 1.5rem;
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: var(--text-color);
            text-align: center;
        }

        .alert {
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            border: 1px solid transparent;
        }

        .alert.error {
            background-color: var(--error-bg);
            color: var(--error-text);
            border-color: var(--error-border);
        }

        .alert.success {
            background-color: var(--success-bg);
            color: var(--success-text);
            border-color: var(--success-border);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        label {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: -0.5rem;
        }

        input[type="text"],
        input[type="email"] {
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
            margin-top: 0.5rem;
        }

        button[type="submit"]:hover {
            background-color: var(--primary-hover);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Crear Cliente</h1>

        <?php if ($mensaje !== ""): ?>
            <div role="<?= $esError ? "alert" : "status" ?>" class="alert <?= $esError ? "error" : "success" ?>">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <form method="post" action="crear.php">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre, ENT_QUOTES, "UTF-8") ?>"
                required placeholder="Ej. Juan Pérez">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, "UTF-8") ?>"
                required placeholder="juan@correo.com">

            <button type="submit">Guardar cliente</button>
        </form>

        <a class="back-link" href="listar.php">&larr; Ver clientes</a>
    </div>

</body>

</html>