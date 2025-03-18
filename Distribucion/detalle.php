<?php
include('../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id_producto = $_GET['id_producto'];

// Consulta SQL para obtener el detalle del producto
$sql = "SELECT pro.id_producto, pro.nombre_producto, pro.descripcion, 
        pro.precio_unitario, pro.impuestos, pro.cantidad, pro.fecha,
        pedi.id_pedido, p.nom_persona AS nombre_cliente, p.apellido_paterno AS ap_p, p.apellido_materno AS ap_m,
        p.codigo_postal, p.calle, p.colonia, p.num_int, p.num_ext,
        p.telefono, p.ciudad, u.correo AS email, pedi.fecha_registro AS fecha_registro, pedi.estatus,
        pd.nom_persona AS distribuidor_nombre, pd.telefono AS distribuidor_telefono
        FROM producto pro
        JOIN pedido pedi ON pedi.id_pedido = pro.id_pedido
        LEFT JOIN persona p ON p.id_persona = pro.id_cliente
        LEFT JOIN usuario u ON u.id_usuario = p.id_usuario
        LEFT JOIN persona pd ON pd.id_persona = pedi.id_distribuidor
        WHERE pro.id_producto = $id_producto";

$result = $conn->query($sql);


// Verificar si el producto existe
if ($result->num_rows > 0) {
    $producto = $result->fetch_assoc();
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detalle del Pedido</title>
        <link rel="stylesheet" href="../CSS/menu.css" type="text/css">
        <link rel="stylesheet" href="../CSS/cabecera.css" type="text/css">
        <link rel="stylesheet" href="../CSS/pie_pagina.css" type="text/css">
        <link rel="stylesheet" href="../CSS/tablas_boton.css" type="text/css">
        <link rel="stylesheet" href="../CSS/formularios.css" type="text/css">
        <link rel="stylesheet" href="../CSS/departamentos.css" type="text/css">
        <link rel="stylesheet" href="../CSS/eliminar.css" type="text/css">
        <style>
            .titulo{
                background-color: #5492cc;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-family: 'Poppins', sans-serif;
                margin-left: 20px;
                margin-top: 20px;
                margin-right: 20px;
            }

            .estatus {
                font-weight: bold;
                padding: 5px 15px;
                border-radius: 15px;
                text-transform: capitalize;
                color: #fff;
                display: inline-block;
                text-align: center;
                margin-right: 20px;
                margin-left: 20px;
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
        <h1 class="titulo">Detalle del Pedido</h1>
        <div class="info">
        <h3 class="sub_titulo">Cliente</h3>
        <p><strong>Cliente:</strong> <?php echo $producto['nombre_cliente']." ".$producto['ap_p']." ".$producto['ap_m']; ?></p>
        <p><strong>Correo del Cliente:</strong> <?php echo $producto['email']; ?></p>
        <p><strong>Teléfono del Cliente:</strong> <?php echo $producto['telefono']; ?></p>
        <h3 class="sub_titulo">Domicilio</h3>
        <p><strong>Domicilio del Cliente:</strong></p>
        <p><strong>Ciudad:</strong> <?php echo $producto['ciudad']; ?></p>
        <p><strong>Colonia:</strong> <?php echo $producto['colonia']; ?></p>
        <p><strong>Calle:</strong> <?php echo $producto['calle']; ?></p>
        <p><strong>Codigo postal:</strong> <?php echo $producto['codigo_postal']; ?></p>
        <p><strong>Número Interior:</strong> <?php echo $producto['num_int']; ?></p>
        <p><strong>Número Exterior:</strong> <?php echo $producto['num_ext']; ?></p>
        <h3 class="sub_titulo">Pedido</h3>
        <p><strong>Pedido:</strong> <?php echo $producto['id_pedido']; ?></p>
        <p><strong>Producto:</strong> <?php echo $producto['nombre_producto']; ?></p>
        <p><strong>Descripción:</strong> <?php echo $producto['descripcion']; ?></p>
        <p><strong>Precio:</strong> <?php echo $producto['precio_unitario']; ?></p>
        <p><strong>Impuestos:</strong> <?php echo $producto['impuestos']; ?></p>
        <p><strong>Cantidad:</strong> <?php echo $producto['cantidad']; ?></p>
        <p><strong>Fecha de Registro:</strong> <?php echo $producto['fecha_registro']; ?></p>
        <p><strong>Fecha del Pedido:</strong> <?php echo $producto['fecha']; ?></p>
        
        <h3 class="sub_titulo">Distribuidor</h3>
        <p><strong>Distribuidor:</strong> <?php echo $producto['distribuidor_nombre']; ?></p>
        <p><strong>Teléfono del Distribuidor:</strong> <?php echo $producto['distribuidor_telefono']; ?></p>
        <form method="POST" action="actualizar_responsable.php" class="form_reg_usuario">
    <label for="distribuidor">Selecciona Distribuidor:</label>
    <select name="distribuidor" id="distribuidor">
        <?php
        // Consulta para obtener distribuidores
        $distribuidores_sql = "
        SELECT persona.id_persona, persona.nom_persona, persona.telefono, persona.apellido_paterno,persona.apellido_materno
        FROM persona
        JOIN usuario ON persona.id_usuario = usuario.id_usuario
        JOIN roles ON usuario.id_rol = roles.id_rol
        WHERE roles.roles = 'Distribuidor'
        ";

        $distribuidores_result = $conn->query($distribuidores_sql);

        // Mostrar los distribuidores en el formulario
        while ($distribuidor = $distribuidores_result->fetch_assoc()) {
            echo '<option value="' . $distribuidor['id_persona'] . '">' . $distribuidor['nom_persona'] . ' ' . $distribuidor['apellido_paterno'] . ' ' . $distribuidor['apellido_materno'] . '</option>';
        }
        ?>
    </select>
    <br><br>
    <input type="hidden" name="id_producto" value="<?php echo $id_producto; ?>">
    <input type="submit" value="Guardar">
</form>


        <h3 class="sub_titulo">Estatus</h3>

            <p><strong>Estatus:</strong>
                <div class="estatus 
                    <?php
                        if ($producto['estatus'] == 'Generado') {
                            echo 'estatus-generado';
                        } elseif ($producto['estatus'] == 'En preparacion') {
                            echo 'estatus-en-preparacion';
                        } elseif ($producto['estatus'] == 'A enviar') {
                            echo 'estatus-a-enviar';
                        } elseif ($producto['estatus'] == 'En distribucion') {
                            echo 'estatus-en-distribucion';
                        } elseif ($producto['estatus'] == 'En camino') {
                            echo 'estatus-en-camino';
                        } elseif ($producto['estatus'] == 'Entregado') {
                            echo 'estatus-entregado';
                        }
                    ?>
                ">
                    <?php echo $producto['estatus']; ?>
                </div>
        </div>
        <br><br>

        <!-- Botones para cambiar el estatus -->
            <?php if ($producto['estatus'] == 'A enviar' || $producto['estatus'] == 'En distribucion') { ?>
                <a class="regresar" href="actualizar_estatus.php?id_producto=<?php echo $producto['id_producto']; ?>&estatus=En distribucion" class="boton">Enviar</a>
            <?php } ?>

            <?php if ($producto['estatus'] == 'En distribucion' || $producto['estatus'] == 'En camino') { ?>
                <a class="regresar" href="actualizar_estatus.php?id_producto=<?php echo $producto['id_producto']; ?>&estatus=En camino" class="boton">En camino</a>
            <?php } ?>

            <?php if ($producto['estatus'] == 'En camino' || $producto['estatus'] == 'Entregado') { ?>
                <a class="regresar" href="actualizar_estatus.php?id_producto=<?php echo $producto['id_producto']; ?>&estatus=Entregado" class="boton">Entregado</a>
            <?php } ?>
            <br><br>
        <a href="distribucion.php" class="regresar">Regresar</a>
    </body>

    <footer class="pie-pagina">
        <div class="grupo-2">
            <small>&copy; 2025 <b>Aplica Central Creativa</b> - Todos los Derechos Reservados.</small>
        </div>
    </footer>


    </html>

<?php
} else {
    echo "<p>Producto no encontrado.</p>";
}
?>
