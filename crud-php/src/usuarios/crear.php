
<?php
// Comprobamos si el formulario se ha enviado mediante POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Comprobamos que se hayan recibido los campos nombre y email.
    if (isset($_POST["nombre"]) && isset($_POST["email"])) {

        // Recogemos el nombre enviado desde el formulario.
        $nombre = trim($_POST["nombre"]);

        // Recogemos el email enviado desde el formulario.
        $email = trim($_POST["email"]);

        // Comprobamos que los campos no estén vacíos.
        if ($nombre === "" || $email === "") {

            // Mostramos un mensaje si falta información.
            echo "Debes rellenar todos los campos.";

        // Comprobamos que el correo electrónico sea válido.
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            // Mostramos un error si el email no tiene formato válido.
            echo "El correo electrónico no es válido.";

        } else {

            // Incluimos la clase de conexión a MariaDB.
            require_once __DIR__ . "/../config/DatabaseService.php";

            // Creamos un objeto de la clase DatabaseService.
            $databaseService = new DatabaseService();

            try {

                // Obtenemos la conexión PDO con MariaDB.
                $connection = $databaseService->getConnection();

                // Preparamos la consulta SQL para insertar el cliente.
                // Los parámetros evitan concatenar datos del usuario.
                $query = "INSERT INTO clientes (nombre, email)
                          VALUES (:nombre, :email)";

                // Preparamos la consulta SQL mediante PDO.
                $stmt = $connection->prepare($query);

                // Asociamos el parámetro :nombre a la variable $nombre.
                $stmt->bindParam(":nombre", $nombre);

                // Asociamos el parámetro :email a la variable $email.
                $stmt->bindParam(":email", $email);

                // Ejecutamos la consulta SQL en MariaDB.
                $resultadoEjecucion = $stmt->execute();

                // Comprobamos si la inserción se ha realizado.
                if ($resultadoEjecucion) {

                    // Mostramos un mensaje de confirmación.
                    echo "Cliente insertado correctamente.";

                } else {

                    // Mostramos un mensaje si la consulta falla.
                    echo "No se ha podido insertar el cliente.";
                }

            // Capturamos posibles errores de conexión o SQL.
            } catch (PDOException $e) {

                // No mostramos detalles internos de la base de datos.
                echo "Se ha producido un error al guardar el cliente.";
            }
        }

    } else {

        // Mostramos un error si faltan los campos del formulario.
        echo "No has enviado la información suficiente.";
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
    <title>Crear cliente</title>
</head>

<body>
    <!-- Mostramos el encabezado de la página. -->
    <h1>Crear cliente</h1>

    <!-- Creamos el formulario que envía los datos mediante POST
         al mismo archivo PHP. -->
    <form method="post" action="crear.php">

        <!-- Etiqueta que identifica el campo nombre. -->
        <label for="nombre">Nombre:</label>

        <!-- Campo para introducir el nombre del cliente. -->
        <input type="text" id="nombre" name="nombre" required>

        <!-- Salto de línea para separar los campos. -->
        <br>

        <!-- Etiqueta que identifica el correo electrónico. -->
        <label for="email">Email:</label>

        <!-- Campo para introducir el correo del cliente. -->
        <input type="email" id="email" name="email" required>

        <!-- Salto de línea para separar los campos. -->
        <br>

        <!-- Botón que envía el formulario al servidor. -->
        <button type="submit">Guardar cliente</button>

    <!-- Cerramos el formulario HTML. -->
    </form>

    <!-- Enlace para consultar los clientes registrados. -->
    <a href="listar.php">Ver clientes</a>

<!-- Cerramos el cuerpo del documento. -->
</body>

<!-- Cerramos el documento HTML. -->
</html>
