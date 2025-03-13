<?php
include('../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../CSS/menu.css" type="text/css">
    <link rel="stylesheet" href="../CSS/cabecera.css" type="text/css">
    <link rel="stylesheet" href="../CSS/pie_pagina.css" type="text/css">
    <link rel="stylesheet" href="../CSS/tablas_boton.css" type="text/css">
    <link rel="stylesheet" href="../CSS/formularios.css" type="text/css">
    <link rel="stylesheet" href="../CSS/departamentos.css" type="text/css">
    <title>Inventario</title>
</head>
    <header class="cabecera_p">
        <div class="cabecera">
            <h1 class="nom_sis">Aplica Central Creativa</h1>
            <a href="../Menu.php"><img src="../Imagenes/acc_logo.png" class="img-logo" alt="Logo"></a>
            <a href="#"><img src="../Imagenes/avatar.png" class="img-avatar" alt="Avatar"></a>
        </div>
        <div class="header">
        <ul class="nav">
                <!-- Usuarios -->
                <li><a href="../Usuario.php">Usuarios</a>
                    <ul class="submenu">
                        <li><a href="../Registro/Registro_Usuario.php">Alta</a></li>
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
    <h1 class="titulo">Inventario</h1>
    
    <?php
    include('../BD/ConexionBD.php');

?>
<table class='tabla_inv'>
    <thead>
        <tr class='cont'>
            <th scope='col'>ID</th>
            <th scope='col'>Articulo</th>
            <th scope='col'>Descripción</th>
            <th scope='col'>Categoría</th>
            <th scope='col'>Precio</th>
            <th scope='col'>Impuestos</th>
            <th scope='col'>Existencias</th>
            <th scope='col'>Fecha Entrada</th>
            <th scope='col'>Fecha Salida</th>
            <th scope='col'>Proveedor</th>
            <th scope='col'>Estatus</th>
            <th scope='col'>Editar</th>
            <th scope='col'>Eliminar</th>
        </tr>
    </thead>
    <tbody>
    <?php
        // Suponiendo que ya tienes la conexión abierta en $conn
        $sql = "SELECT i.id_inventario, i.id_articulo, a.descripcion, a.nombre_art, a.categoria, a.precio, a.impuestos,
         a.existencias, a.fecha_entrada, a.fecha_salida, u.nom_usuario 
                FROM Inventario i
                JOIN Articulos a ON i.id_articulo = a.id_articulo
                LEFT JOIN Usuario u ON a.id_usuario = u.id_usuario";
        
        $result = $conn->query($sql);

        while ($fila = $result->fetch_assoc()) :
    ?>
        <tr>
            <th scope='row'><?= htmlspecialchars($fila['id_inventario']) ?></th>
            <td><?= htmlspecialchars($fila['nombre_art']) ?></td>
            <td><?= htmlspecialchars($fila['descripcion']) ?></td>
            <td><?= htmlspecialchars($fila['categoria']) ?></td>
            <td><?= htmlspecialchars($fila['precio']) ?></td>
            <td><?= htmlspecialchars($fila['impuestos']) ?></td>
            <td><?= htmlspecialchars($fila['existencias']) ?></td>
            <td><?= htmlspecialchars($fila['fecha_entrada']) ?></td>
            <td><?= htmlspecialchars($fila['fecha_salida']) ?></td>
            <td><?= htmlspecialchars($fila['nom_usuario']) ?></td>
            <td><a href='Editar/Modificar_Articulo.php?id_inventario=<?= htmlspecialchars($fila['id_inventario']) ?>' class='editar'>Editar</a></td>
            <td><a href='Eliminar/Eliminar_Articulo.php?id_inventario=<?= htmlspecialchars($fila['id_inventario']) ?>'>Eliminar</a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

        <a href="../Usuario.php" class="regresar">Regresar</a>
</body>
</html>

    
    </tbody>

    <footer class="pie-pagina">
        <div class="grupo-2">
            <small>&copy; 2025 <b>Aplica Central Creativa</b> - Todos los Derechos Reservados.</small>
        </div>
    </footer>
</body>
</html>
