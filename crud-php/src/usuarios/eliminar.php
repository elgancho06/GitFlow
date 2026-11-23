<?php
// Incluimos la clase de conexión a MariaDB ( Poner /config ).
require_once __DIR__ . "/../config/DatabaseService.php";
// Creamos un objeto de la clase DatabaseService.
$databaseService = new DatabaseService();
// Inicializamos las variables para la vista.
$id = "";
$cliente = null;
$eliminado = false;
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
 $id = trim($_POST["id"] ?? "");
 if ($id === "") {
 $mensaje = "El identificador del cliente no es válido.";
 } else {
 try {
 $connection = $databaseService->getConnection();
 $query = "DELETE FROM clientes WHERE id = :id";
 $stmt = $connection->prepare($query);
 $stmt->execute([":id" => $id]);
 if ($stmt->rowCount() > 0) {
 $eliminado = true;
 $mensaje = "Cliente eliminado correctamente.";
 } else {
 $mensaje = "El cliente solicitado no existe.";
 }
 } catch (PDOException $e) {
 $mensaje = "Se ha producido un error al eliminar el
cliente.";
 }
 }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
 $id = trim($_GET["id"]);
 if ($id === "") {
 $mensaje = "El identificador del cliente no es válido.";
 } else {
 try {
 $connection = $databaseService->getConnection();
 $query = "SELECT id, nombre FROM clientes WHERE id = :id";
 $stmt = $connection->prepare($query);
 $stmt->execute([":id" => $id]);
 $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
 if (!$cliente) {
 $mensaje = "El cliente solicitado no existe.";
 }
 } catch (PDOException $e) {
 $mensaje = "Se ha producido un error al buscar el
cliente.";
 }
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
 <title>Eliminar cliente</title>
</head>
<body>
 <!-- Mostramos el encabezado de la página. -->
 <h1>Eliminar cliente</h1>
 <?php if ($mensaje !== ""): ?>
 <p><?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?></p>
 <?php endif; ?>
 <form method="get" action="eliminar.php">
 <label for="id">ID del cliente:</label>
 <input type="number" id="id" name="id" value="<?=
htmlspecialchars($id, ENT_QUOTES, "UTF-8") ?>" required>
 <button type="submit">Buscar cliente</button>
 </form>
 <?php if (!$eliminado && $cliente): ?>
 <p>¿Estás seguro de que deseas eliminar al cliente
 <strong><?= htmlspecialchars($cliente["nombre"],
ENT_QUOTES, "UTF-8") ?></strong>
 (ID <?= htmlspecialchars($cliente["id"], ENT_QUOTES,
"UTF-8") ?>)?
 </p>
 <form method="post" action="eliminar.php">
 <input type="hidden" name="id" value="<?=
htmlspecialchars($id, ENT_QUOTES, "UTF-8") ?>">
 <button type="submit">Confirmar eliminación</button>
 </form>
 <?php endif; ?>
 <!-- Enlace para volver al listado de clientes. -->
 <a href="listar.php">Ver clientes</a>
<!-- Cerramos el cuerpo del documento. -->
</body>
<!-- Cerramos el documento HTML. -->
</html>
