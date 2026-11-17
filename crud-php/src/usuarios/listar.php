<?php
// Incluimos el archivo que contiene la clase de conexión.
require_once __DIR__ . "/../config/DatabaseService.php";

// Creamos un objeto de la clase DatabaseService.
$databaseService = new DatabaseService();

// Inicializamos un array vacío para guardar los clientes.
$clientes = [];

// Inicializamos la variable que contendrá posibles errores.
$error = "";

try {
    // Obtenemos la conexión con la base de datos MariaDB.
    $connection = $databaseService->getConnection();

    // Definimos la consulta que recupera todos los clientes.
    $query = "SELECT id, nombre, email FROM clientes";

    // Ejecutamos la consulta SELECT en la base de datos.
    $stmt = $connection->query($query);

    // Recuperamos todas las filas como arrays asociativos.
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Capturamos los posibles errores de conexión o SQL.
} catch (PDOException $e) {
    // Guardamos un mensaje genérico de error.
    $error = "No se han podido consultar los clientes.";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de clientes</title>
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-color: #1f2937;
            --border-color: #e5e7eb;
            --error-bg: #fee2e2;
            --error-text: #991b1b;
            --error-border: #f87171;
            --table-header-bg: #f9fafb;
            --table-hover-bg: #f9fafb;
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
            max-width: 700px;
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
            background-color: var(--error-bg);
            color: var(--error-text);
            border: 1px solid var(--error-border);
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        th,
        td {
            padding: 0.75rem 1rem;
        }

        th {
            background-color: var(--table-header-bg);
            font-weight: 600;
            color: #4b5563;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            border-bottom: 1px solid var(--border-color);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: var(--table-hover-bg);
        }

        .empty-message {
            text-align: center;
            color: #6b7280;
            padding: 1.5rem 0;
            font-size: 0.95rem;
        }

        .action-link {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            text-align: center;
            font-size: 1rem;
            transition: background-color 0.2s;
            width: 100%;
            box-sizing: border-box;
        }

        .action-link:hover {
            background-color: var(--primary-hover);
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Listado de Clientes</h1>

        <?php if ($error !== ""): ?>
            <div class="alert" role="alert">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php else: ?>
            <?php if (count($clientes) > 0): ?>
                <div class="table-container">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                        </tr>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= (int) $cliente["id"] ?></td>
                                <td><?= htmlspecialchars($cliente["nombre"], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($cliente["email"], ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php else: ?>
                <p class="empty-message">No hay clientes registrados.</p>
            <?php endif; ?>
        <?php endif; ?>

        <a class="action-link" href="crear.php">+ Crear nuevo cliente</a>
    </div>

</body>

</html>