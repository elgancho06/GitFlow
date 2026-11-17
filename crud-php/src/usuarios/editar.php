<?php
require_once __DIR__ . "/../config/DatabaseService.php";

$databaseService = new DatabaseService();

$id = "";
$cliente = null;
$mensaje = "";
$esError = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = trim($_POST["id"] ?? "");
    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if ($id === "") {
        $mensaje = "Debes indicar el ID del cliente.";
        $esError = true;
    } elseif ($nombre === "" && $email === "") {
        $mensaje = "Introduce un nombre o un email para actualizar.";
        $esError = true;
    } elseif ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El correo electrónico no es válido.";
        $esError = true;
    } else {
        try {
            $connection = $databaseService->getConnection();

            $query = "SELECT id FROM clientes WHERE id = :id";
            $stmt = $connection->prepare($query);
            $stmt->execute([":id" => $id]);

            if ($stmt->fetchColumn() === false) {
                $cliente = false;
                $mensaje = "El cliente con ID " . htmlspecialchars($id, ENT_QUOTES, "UTF-8") . " no existe.";
                $esError = true;
            } else {
                $sets = [];
                $params = [":id" => $id];

                if ($nombre !== "") {
                    $sets[] = "nombre = :nombre";
                    $params[":nombre"] = $nombre;
                }

                if ($email !== "") {
                    $sets[] = "email = :email";
                    $params[":email"] = $email;
                }

                $query = "UPDATE clientes SET " . implode(", ", $sets) . " WHERE id = :id";
                $stmt = $connection->prepare($query);
                $stmt->execute($params);

                $query = "SELECT id, nombre, email FROM clientes WHERE id = :id";
                $stmt = $connection->prepare($query);
                $stmt->execute([":id" => $id]);
                $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
                $mensaje = "Cliente actualizado correctamente.";
            }
        } catch (PDOException $e) {
            $mensaje = "Se ha producido un error al actualizar el cliente.";
            $esError = true;
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $id = trim($_GET["id"]);
}

if ($id !== "" && $cliente === null) {
    try {
        $connection = $databaseService->getConnection();
        $query = "SELECT id, nombre, email FROM clientes WHERE id = :id";
        $stmt = $connection->prepare($query);
        $stmt->execute([":id" => $id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $cliente = $resultado;
        } else {
            $cliente = false;
            $mensaje = "El cliente con ID " . htmlspecialchars($id, ENT_QUOTES, "UTF-8") . " no existe.";
            $esError = true;
        }
    } catch (PDOException $e) {
        $mensaje = "Se ha producido un error al cargar los datos del cliente.";
        $esError = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar cliente</title>
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

        h2 {
            font-size: 1.2rem;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
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

        input[type="number"],
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
        }

        button[type="submit"]:hover {
            background-color: var(--primary-hover);
        }

        .cliente-info {
            background-color: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .cliente-info p {
            margin: 0.4rem 0;
            font-size: 0.9rem;
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
        <h1>Editar Cliente</h1>

        <?php if ($mensaje !== ""): ?>
            <div role="<?= $esError ? "alert" : "status" ?>" class="alert <?= $esError ? "error" : "success" ?>">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <form method="get" action="editar.php">
            <label for="id">ID del cliente:</label>
            <input type="number" id="id" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, "UTF-8") ?>" required
                placeholder="Ej. 1">
            <button type="submit">Buscar cliente</button>
        </form>

        <?php if ($cliente): ?>
            <div class="cliente-info">
                <h2>Cliente #<?= htmlspecialchars($cliente["id"], ENT_QUOTES, "UTF-8") ?></h2>
                <p><strong>Nombre actual:</strong> <?= htmlspecialchars($cliente["nombre"], ENT_QUOTES, "UTF-8") ?></p>
                <p><strong>Email actual:</strong> <?= htmlspecialchars($cliente["email"], ENT_QUOTES, "UTF-8") ?></p>
            </div>

            <form method="post" action="editar.php">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, "UTF-8") ?>">

                <label for="nombre">Nuevo nombre (opcional):</label>
                <input type="text" id="nombre" name="nombre" placeholder="Dejar en blanco si no se cambia">

                <label for="email">Nuevo email (opcional):</label>
                <input type="email" id="email" name="email" placeholder="nuevo@correo.com">

                <button type="submit">Actualizar cliente</button>
            </form>
        <?php endif; ?>

        <a class="back-link" href="listar.php">&larr; Volver al listado de clientes</a>
    </div>

</body>

</html>