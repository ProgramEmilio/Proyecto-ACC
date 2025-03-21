<?php
include('../BD/ConexionBD.php');
session_start();
// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
$id_distribuidor_usuario = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribución</title>
    <link rel="stylesheet" href="../CSS/menu.css" type="text/css">
    <link rel="stylesheet" href="../CSS/cabecera.css" type="text/css">
    <link rel="stylesheet" href="../CSS/pie_pagina.css" type="text/css">
    <link rel="stylesheet" href="../CSS/tablas_boton.css" type="text/css">
    <link rel="stylesheet" href="../CSS/formularios.css" type="text/css">
    <link rel="stylesheet" href="../CSS/departamentos.css" type="text/css">
    <link rel="stylesheet" href="../CSS/eliminar.css" type="text/css">
    <style>
        .estatus {
            font-weight: bold;
            padding: 5px 15px;
            border-radius: 15px;
            text-transform: capitalize;
            color: #fff;
            display: inline-block;
            text-align: center;
            margin-right: 10px;
            width: auto;
        }

        .estatus-generado {
            background-color: #9E9E9E; /* Gris */
        }

        .estatus-en-preparacion {
            background-color: #FF9800; /* Naranja */
        }

        .estatus-a-enviar {
            background-color: #FFEB3B; /* Amarillo */
        }

        .estatus-en-distribucion {
            background-color: #4CAF50; /* Verde */
        }

        .estatus-en-camino {
            background-color: #2196F3; /* Azul */
        }

        .estatus-entregado {
            background-color: #F44336; /* Rojo */
        }

        .estatus-Devolucion {
            background-color: #DC3545; /* Rojo oscuro */
        }

        /* Ajuste responsivo */
        @media (max-width: 768px) {
            .estatus {
                font-size: 14px;
                padding: 5px 10px;
            }
        }
    </style>
</head>

<header class="cabecera_p">
    <div class="cabecera">
        <h1 class="nom_sis">Aplica Central Creativa</h1>
        <a href="../Menu.php"><img src="../Imagenes/acc_logo.png" class="img-logo" alt="Logo"></a>
        <a href="#"><img src="../Imagenes/avatar.png" class="img-avatar" alt="Avatar"></a>
    </div>
    <div class="header">
        <ul class="nav">
            <li><a href="../Usuario.php">Usuarios</a>
                <ul class="submenu">
                    <li><a href="../Registro/Registro_Usuario.php">Alta</a></li>
                </ul>
            </li>
            <li><a href="#">Proveedor</a></li>
            <li><a href="#">Ventas</a></li>
            <li><a href="#">Compras</a></li>
            <li><a href="#">Inventario</a></li>
            <li><a href="#">Distribución</a></li>
            <li><a href="#">Producción</a></li>
        </ul>
    </div>
</header>

<body>
    <h1 class="titulo">Distribución</h1>

    <?php
    // Consulta SQL para obtener los productos y su información
    $sql = "SELECT pro.id_producto, pro.nombre_producto, pro.cantidad, pro.fecha, pedi.id_pedido, p.nom_persona AS nombre_cliente,p.apellido_paterno AS ap_p, p.apellido_materno as ap_m, pedi.fecha_registro AS fecha_registro, pedi.estatus FROM producto pro JOIN pedido pedi ON pedi.id_pedido = pro.id_pedido LEFT JOIN persona p ON p.id_persona = pro.id_cliente WHERE pedi.estatus = 'A enviar' OR pedi.estatus = 'En distribucion' OR pedi.estatus = 'En camino' OR pedi.estatus = 'Entregado' OR pedi.estatus = 'Devolucion'";

    $result = $conn->query($sql);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        echo "<table class='tabla'>
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Impuestos</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Estatus</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>";

        while ($fila = $result->fetch_assoc()) {
            // Asignar clase de estatus
            $estatus_class = '';
            switch ($fila['estatus']) {
                case 'Generado':
                    $estatus_class = 'estatus-generado';
                    break;
                case 'En preparacion':
                    $estatus_class = 'estatus-en-preparacion';
                    break;
                case 'A enviar':
                    $estatus_class = 'estatus-a-enviar';
                    break;
                case 'En distribucion':
                    $estatus_class = 'estatus-en-distribucion';
                    break;
                case 'En camino':
                    $estatus_class = 'estatus-en-camino';
                    break;
                case 'Entregado':
                    $estatus_class = 'estatus-entregado';
                    break;
                case 'devolucion':
                    $estatus_class = 'estatus-devolucion'; // Asegúrate de que esta clase esté correcta
                    break;
                default:
                    $estatus_class = 'estatus-generado'; // Default
            }

            echo "<tr>
                    <td>" . $fila['id_producto'] . "</td>
                    <td>" . $fila['nombre_producto'] . "</td>
                    <td>" . $fila['descripcion'] . "</td>
                    <td>" . $fila['precio_unitario'] . "</td>
                    <td>" . $fila['impuestos'] . "</td>
                    <td>" . $fila['cantidad'] . "</td>
                    <td>" . $fila['fecha'] . "</td>
                    <td><div class='estatus $estatus_class'>" . $fila['estatus'] . "</div></td>
                    <td><a href='detalle.php?id_producto=" . $fila['id_producto'] . "'>Ver Detalle</a></td>
                </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron productos para envío.</p>";
    }
    ?>

    <a href="../Usuario.php" class="regresar">Regresar</a>

</body>

<footer class="pie-pagina">
    <div class="grupo-2">
        <small>&copy; 2025 <b>Aplica Central Creativa</b> - Todos los Derechos Reservados.</small>
    </div>
</footer>
</html>
