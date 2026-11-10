<?php
$mensaje_conexion = "";
try {
    require_once 'config/DatabaseService.php';
    $databaseService = new DatabaseService();
    $connection = $databaseService->getConnection();
    
    if ($connection) {
        $mensaje_conexion = "<p style='color: #4CAF50; font-weight: bold;'>✅ Conexión a la base de datos: ¡EXITOSA!</p>";
    }
} catch (Exception $e) {
    $mensaje_conexion = "<p style='color: #FFC107; font-weight: bold;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo de select</title>
    <link href="https://fonts.googleapis.com/css?family=Mandali&display=swap" rel="stylesheet">

    <style>
        .content {
            width: 100%;
            background-color: darkred;
            padding: 30px;
            color: white;
            text-align: center;
        }

        body {
            font-family: 'Mandali', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        button {
            margin: 5px;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
  <div class="content">
    <h1 class="titulo">Ejercicio 1 - PHP y select</h1>
    
    <!-- Aquí imprimimos el resultado de la comprobación PHP -->
    <?= $mensaje_conexion ?>

    <div class="contenedor-centrado">
      <button type="button" onclick="window.location.href='usuarios/listar.php'">Consultar clientes</button>
      <button type="button" onclick="window.location.href='usuarios/crear.php'">Inserta un cliente</button>
      <button type="button" onclick="window.location.href='select-productos.php'">Consultar productos</button>
      <button type="button" onclick="window.location.href='insert-producto.php'">Inserta un producto</button>
      <button type="button" onclick="window.location.href='productos.php'">Consultar e insertar productos</button>
    </div>
  </div>

</body>
</html>
