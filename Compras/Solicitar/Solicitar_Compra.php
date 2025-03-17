<?php
include('../../BD/ConexionBD.php');
session_start(); // Iniciar sesión para obtener el ID del usuario logueado

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta para obtener los artículos disponibles
$sql = "SELECT id_articulo, nombre_articulo, descripcion, categoria, precio, existencias FROM articulos";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['solicitar'])) {
    $id_articulo = $_POST['id_articulo'];
    $cantidad = $_POST['cantidad'];
    $id_comprador_usuario = 1651; // Se debe obtener de la sesión en un futuro
    
    if ($cantidad > 0) {
        // Insertar la solicitud de compra
        $insert_sql = "INSERT INTO solicitud_compra (id_comprador_usuario, id_proveedor_usuario, descripcion, total, fecha_registro, estatus) 
                       VALUES (?, NULL, ?, ?, NOW(), 'generada')";

        $stmt = $conn->prepare($insert_sql);
        $descripcion = "Compra solicitada";
        $total = 0; // Se actualizará después si es necesario
        
        // Corregimos los parámetros en bind_param()
        $stmt->bind_param("isd", $id_comprador_usuario, $descripcion, $total);
        $stmt->execute();

        // Obtener el ID de la solicitud creada
        $id_solicitud = $stmt->insert_id;
        $stmt->close();

        // Obtener el precio del artículo
        $query_precio = "SELECT precio FROM articulos WHERE id_articulo = ?";
        $stmt = $conn->prepare($query_precio);
        $stmt->bind_param("i", $id_articulo);
        $stmt->execute();
        $stmt->bind_result($precio_unitario);
        $stmt->fetch();
        $stmt->close();

        // Calcular el total
        $total_detalle = $cantidad * $precio_unitario;

        // Insertar el detalle de la solicitud de compra
        $insert_detalle_sql = "INSERT INTO solicitud_compra_detalle (id_solicitud, id_articulo, cantidad, precio_unitario, total, fecha_registro) 
                               VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($insert_detalle_sql);
        $stmt->bind_param("iiidd", $id_solicitud, $id_articulo, $cantidad, $precio_unitario, $total_detalle);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Solicitud enviada correctamente.'); window.location.href='Solicitar_Compra.php';</script>";
    } else {
        echo "<script>alert('Ingrese una cantidad válida.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../../CSS/menu.css" type="text/css">
    <link rel="stylesheet" href="../../CSS/cabecera.css" type="text/css">
    <link rel="stylesheet" href="../../CSS/pie_pagina.css" type="text/css">
    <link rel="stylesheet" href="../../CSS/tablas_boton.css" type="text/css">
    <link rel="stylesheet" href="../../CSS/formularios.css" type="text/css">
    <link rel="stylesheet" href="../../CSS/departamentos.css" type="text/css">
</head>
<header class="cabecera_p">
        <div class="cabecera">
            <h1 class="nom_sis">Aplica Central Creativa</h1>
            <a href="../../Menu.php"><img src="../../Imagenes/acc_logo.png" class="img-logo" alt="Logo"></a>
            <a href="#"><img src="../../Imagenes/avatar.png" class="img-avatar" alt="Avatar"></a>
        </div>
        <div class="header">
        <ul class="nav">
                <!-- Usuarios -->
                <li><a href="Usuario.php">Usuarios</a>
                    <ul class="submenu">
                        <li><a href="Registro/Registro_Usuario.php">Alta</a></li>
                    </ul>
                </li>
                <!-- Proveedor -->
                <li><a href="#">Proveedor</a>
                </li>

                <!-- Ventas -->
                <li><a href="#">Ventas</a>
                </li>

                <!-- Compras -->
                <li><a href="#">Compras</a>
                </li>

              <!-- Inventario -->
                <li><a href="#">Inventario</a>
                </li>

                <!-- Distribucion -->
                <li><a href="#">Distribucion</a>
                </li>

             <!-- Produccion -->
                <li><a href="#">Produccion</a>
                </li>
        </ul>
    </div>
</header>
<body>
    <h1 class="titulo">Catálogo de Artículos</h1>

    <table class='tabla'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Existencias</th>
                <th>Cantidad solicitada</th>
                <th>Solicitar</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($fila['id_articulo']) ?></td>
                <td><?= htmlspecialchars($fila['nombre_articulo']) ?></td>
                <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                <td><?= htmlspecialchars($fila['categoria']) ?></td>
                <td><?= number_format($fila['precio'], 2) ?></td>
                <td><?= htmlspecialchars($fila['existencias']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_articulo" value="<?= $fila['id_articulo'] ?>">
                        <input class='contenido' type="number" name="cantidad" min="1" required>
                </td>
                <td>
                        <button class='regresar' type="submit" name="solicitar">Solicitar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
