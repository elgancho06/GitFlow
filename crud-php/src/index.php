<?php
$mensaje_conexion = "";
try {
    require_once 'config/DatabaseService.php';
    $databaseService = new DatabaseService();
    $connection = $databaseService->getConnection();

    if ($connection) {
        $mensaje_conexion = "<div class='alert success' role='status'>✅ Conexión a la base de datos: ¡EXITOSA!</div>";
    }
} catch (Exception $e) {
    $mensaje_conexion = "<div class='alert error' role='alert'>❌ Error de conexión: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda ElectroLitrona - Inicio</title>
    <link href="https://fonts.googleapis.com/css?family=Mandali&display=swap" rel="stylesheet">

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
            --success-bg: #dcfce7;
            --success-text: #166534;
            --success-border: #4ade80;
            --danger-color: #dc2626;
            --danger-hover: #b91c1c;
        }

        body {
            font-family: 'Mandali', system-ui, -apple-system, sans-serif;
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
            text-align: center;
        }

        h1 {
            font-size: 1.75rem;
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }

        .alert {
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
            text-align: left;
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

        .menu-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 0.875rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s, transform 0.1s;
            width: 100%;
            box-sizing: border-box;
            font-family: inherit;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        button:active {
            transform: scale(0.98);
        }

        /* Estilo preparado y diferenciado para el futuro botón de eliminar */
        button.btn-danger {
            background-color: var(--danger-color);
        }

        button.btn-danger:hover {
            background-color: var(--danger-hover);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tienda ElectroLitrona</h1>

        <!-- Aquí imprimimos el resultado de la comprobación PHP con formato adaptado -->
        <?= $mensaje_conexion ?>

        <div class="menu-buttons">
            <button type="button" onclick="window.location.href='usuarios/listar.php'">Consultar clientes</button>
            <button type="button" onclick="window.location.href='usuarios/crear.php'">Inserta un cliente</button>
            <button type="button" onclick="window.location.href='usuarios/editar.php'">Editar un cliente</button>

            <!-- Botón preparado para cuando implementes la eliminación de usuarios -->
            <!-- <button type="button" class="btn-danger" onclick="window.location.href='usuarios/eliminar.php'">Eliminar un cliente</button> -->
        </div>
    </div>
</body>

</html>