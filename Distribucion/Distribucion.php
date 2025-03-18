<?php
include('../BD/ConexionBD.php');
session_start();
if (isset($_POST['cerrar_sesion'])) {
    session_destroy(); // Destruye la sesión
    header("Location: ../Login/login.php"); // Redirige a la página de inicio de sesión
    exit();
}
// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
$id_distribuidor_usuario = $_SESSION['id_usuario'];
$rol_usuario = $_SESSION['id_rol'];
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
    <link rel="stylesheet" href="../CSS/cabecera2.css" type="text/css">

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
        <a href="../Inicio/inicio.php"><img src="../Imagenes/acc_logo.png" class="img-logo" alt="Logo"></a>
        <div class="logout-container">
        <form method="POST">
            <button type="submit" name="cerrar_sesion" class="btn_logout">Cerrar Sesión</button>
        </form>
    </div>
    </div>
    <div class="header">
    <ul class="nav">
    <?php if ($rol_usuario == '1') { // ADMINISTRADOR ?>
        <li><a href="../Home/inicio.php">Cliente</a></li>
        <li><a href="../Usuarios/Usuario.php">Usuario</a>
            <ul class="submenu">
                <li><a href="../Registro/Registro_Usuario.php">Alta</a></li>
            </ul>
        </li>
        <li><a href="#">Ventas</a></li>
        <li><a href="../Compras/Cotizacion/Cotizar.php">Proveedor</a></li>
        <li><a href="#">Compras</a>
            <ul class="submenu">
                <li><a href="../Compras/Solicitar/Solicitar_Compra.php">Solicitar</a></li>
                <li><a href="../Compras/Aprobar/Aprobar_Compra.php">Aprobar</a></li>
            </ul>
        </li>
        <li><a href="../Inventario/Inventario.php">Inventario</a></li>
        <li><a href="../Produccion/pro.php">Producción</a></li>
        <li><a href="../Distribucion/Distribucion.php">Distribución</a></li>

    <?php } else { // PARA OTROS ROLES ?>
        <?php if ($rol_usuario == '5') { ?>
            <li><a href="#">Ventas</a></li>
        <?php } ?>

        <?php if ($rol_usuario == '3') { ?>
            <li><a href="../Compras/Cotizacion/Cotizar.php">Proveedor</a></li>
        <?php } ?>

        <?php if ($rol_usuario == '4') { ?>
            <li><a href="#">Compras</a>
                <ul class="submenu">
                    <li><a href="../Compras/Solicitar/Solicitar_Compra.php">Solicitar</a></li>
                    <li><a href="../Compras/Aprobar/Aprobar_Compra.php">Aprobar</a></li>
                </ul>
            </li>
        <?php } ?>

        <?php if ($rol_usuario == '8') { ?>
            <li><a href="../Inventario/Inventario.php">Inventario</a></li>
        <?php } ?>

        <?php if ($rol_usuario == '6') { ?>
            <li><a href="../Produccion/pro.php">Producción</a></li>
        <?php } ?>

        <?php if ($rol_usuario == '7') { ?>
            <li><a href="../Distribucion/Distribucion.php">Distribución</a></li>
        <?php } ?>
    <?php } ?>
</ul>
</div>
</header>

<body>
    <h1 class="titulo">Distribución</h1>

    <?php
    // Consulta SQL para obtener los productos y su información
    $sql = "SELECT pro.id_producto, pro.nombre_producto, pro.descripcion, 
            pro.precio_unitario, pro.impuestos, pro.cantidad, pro.fecha,
            pedi.id_pedido, p.nom_persona AS nombre_cliente,p.apellido_paterno AS ap_p, p.apellido_materno as ap_m,
            pedi.fecha_registro AS fecha_registro, pedi.estatus
            FROM producto pro
            JOIN pedido pedi ON pedi.id_pedido = pro.id_pedido
            LEFT JOIN persona p ON p.id_persona = pro.id_cliente
            WHERE pedi.estatus = 'A enviar' OR pedi.estatus = 'En distribucion' OR pedi.estatus = 'En camino' OR pedi.estatus = 'Entregado'";

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

<?php
include('../Nav/footer.php');
?>
</html>
