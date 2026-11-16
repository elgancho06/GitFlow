
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

<!-- Indicamos que utilizamos HTML5. -->
<!DOCTYPE html>

<!-- Establecemos el idioma español. -->
<html lang="es">

<head>
    <!-- Permitimos caracteres especiales como tildes y ñ. -->
    <meta charset="UTF-8">

    <!-- Definimos el título de la pestaña. -->
    <title>Listado de clientes</title>
</head>

<body>
    <!-- Mostramos el título principal. -->
    <h1>Listado de clientes</h1>

    <!-- Comprobamos si existe algún mensaje de error. -->
    <?php if ($error !== ""): ?>

        <!-- Mostramos el error escapando su contenido. -->
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>

    <!-- Si no existen errores, mostramos los resultados. -->
    <?php else: ?>

        <!-- Comprobamos si se han encontrado clientes. -->
        <?php if (count($clientes) > 0): ?>

            <!-- Creamos una tabla HTML con bordes visibles. -->
            <table border="1">

                <!-- Creamos la fila de encabezados. -->
                <tr>
                    <!-- Columna del identificador del cliente. -->
                    <th>ID</th>

                    <!-- Columna del nombre del cliente. -->
                    <th>Nombre</th>

                    <!-- Columna del correo electrónico. -->
                    <th>Email</th>
                </tr>

                <!-- Recorremos todos los clientes recuperados. -->
                <?php foreach ($clientes as $cliente): ?>

                    <!-- Creamos una fila por cada cliente. -->
                    <tr>
                        <!-- Mostramos el ID convertido en entero. -->
                        <td><?= (int) $cliente["id"] ?></td>

                        <!-- Mostramos el nombre de forma segura. -->
                        <td><?= htmlspecialchars($cliente["nombre"], ENT_QUOTES, 'UTF-8') ?></td>

                        <!-- Mostramos el correo de forma segura. -->
                        <td><?= htmlspecialchars($cliente["email"], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>

                <!-- Terminamos de recorrer los clientes. -->
                <?php endforeach; ?>

            <!-- Cerramos la tabla HTML. -->
            </table>

        <!-- Si no existen clientes, mostramos un mensaje. -->
        <?php else: ?>

            <!-- Informamos de que la tabla está vacía. -->
            <p>No hay clientes registrados.</p>

        <!-- Finalizamos la comprobación de clientes. -->
        <?php endif; ?>

    <!-- Finalizamos la comprobación de errores. -->
    <?php endif; ?>

    <!-- Enlace para volver al formulario de creación. -->
    <a href="crear.php">Crear nuevo cliente</a>

<!-- Cerramos el cuerpo de la página. -->
</body>

<!-- Cerramos el documento HTML. -->
</html>
