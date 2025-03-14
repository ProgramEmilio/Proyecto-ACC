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
    <title>Inventario</title>
    <link rel="stylesheet" href="../CSS/menu.css" type="text/css">
    <link rel="stylesheet" href="../CSS/cabecera.css" type="text/css">
    <link rel="stylesheet" href="../CSS/pie_pagina.css" type="text/css">
    <link rel="stylesheet" href="../CSS/tablas_boton.css" type="text/css">
    <link rel="stylesheet" href="../CSS/formularios.css" type="text/css">
    <link rel="stylesheet" href="../CSS/departamentos.css" type="text/css">
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

    <table class='tabla_inv'>
        <thead>
            <tr class='cont'>
                <th scope='col'>ID Artículo</th>
                <th scope='col'>Artículo</th>
                <th scope='col'>Descripción</th>
                <th scope='col'>Categoría</th>
                <th scope='col'>Existencias</th>
                <th scope='col'>Fecha Entrada</th>
                <th scope='col'>Editar</th>
                <th scope='col'>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta SQL para obtener los artículos y su información
            $sql = "SELECT * FROM articulos";
            
            $result = $conn->query($sql);

            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                while ($fila = $result->fetch_assoc()) :
            ?>
                    <tr>
                        <th scope='row'><?= htmlspecialchars($fila['id_articulo']) ?></th>
                        <td><?= htmlspecialchars($fila['nombre_articulo']) ?></td>
                        <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                        <td><?= htmlspecialchars($fila['categoria']) ?></td>
                        <td><?= htmlspecialchars($fila['existencias']) ?></td>
                        <td><?= htmlspecialchars($fila['fecha_registro']) ?></td>
                        <td><a href='Editar/Modificar_I.php?id_articulo=<?= htmlspecialchars($fila['id_articulo']) ?>' class='editar'>Editar</a></td>
                        <td><a href='Eliminar/Eliminar_Inventario.php?id_articulo=<?= htmlspecialchars($fila['id_articulo']) ?>'>Eliminar</a></td>
                    </tr>
            <?php
                endwhile;
            } else {
                echo "<tr><td colspan='9'>No hay artículos en el inventario.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="../Usuario.php" class="regresar">Regresar</a>
</body>
</html>

<footer class="pie-pagina">
    <div class="grupo-2">
        <small>&copy; 2025 <b>Aplica Central Creativa</b> - Todos los Derechos Reservados.</small>
    </div>
</footer>
