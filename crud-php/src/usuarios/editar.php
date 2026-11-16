<?php
// Incluimos la clase de conexión a MariaDB.
require_once __DIR__ . "/../config/DatabaseService.php";

// Creamos un objeto de la clase DatabaseService.
$databaseService = new DatabaseService();

// Inicializamos las variables para rellenar el formulario.
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
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mensaje = "Se ha producido un error al cargar los datos del cliente.";
        $esError = true;
    }
}
?>

<!-- Declaramos que el documento utiliza HTML5. -->
<!DOCTYPE html>

<!-- Indicamos que el idioma de la página es español. -->
<html lang="es">

<head>
    <!-- Configuramos la codificación para admitir tildes y ñ. -->
    <meta charset="UTF-8">

    <!-- Establecemos el título de la pestaña del navegador. -->
    <title>Editar cliente</title>
</head>

<body>
    <!-- Mostramos el encabezado de la página. -->
    <h1>Editar cliente</h1>

    <?php if ($mensaje !== ""): ?>
        <p role="<?= $esError ? "alert" : "status" ?>"
           style="<?= $esError ? "color: #b00020; background-color: #fde7e9; border: 1px solid #b00020;" : "color: #176b2c; background-color: #e8f5e9; border: 1px solid #176b2c;" ?> padding: 0.75rem; font-weight: bold;">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <form method="get" action="editar.php">
        <label for="id">ID del cliente:</label>
        <input type="number" id="id" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, "UTF-8") ?>" required>
        <button type="submit">Buscar cliente</button>
    </form>

    <?php if ($cliente): ?>
        <h2>Cliente #<?= htmlspecialchars($cliente["id"], ENT_QUOTES, "UTF-8") ?></h2>
        <p>Nombre actual: <?= htmlspecialchars($cliente["nombre"], ENT_QUOTES, "UTF-8") ?></p>
        <p>Email actual: <?= htmlspecialchars($cliente["email"], ENT_QUOTES, "UTF-8") ?></p>

        <form method="post" action="editar.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, "UTF-8") ?>">

            <label for="nombre">Nuevo nombre (opcional):</label>
            <input type="text" id="nombre" name="nombre">
            <br>

            <label for="email">Nuevo email (opcional):</label>
            <input type="email" id="email" name="email">
            <br>

            <button type="submit">Actualizar cliente</button>
        </form>
    <?php endif; ?>

    <!-- Enlace para volver al listado de clientes. -->
    <a href="listar.php">Ver clientes</a>

<!-- Cerramos el cuerpo del documento. -->
</body>

<!-- Cerramos el documento HTML. -->
</html>