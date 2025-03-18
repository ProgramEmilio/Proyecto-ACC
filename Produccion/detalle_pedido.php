<?php
// Conexion a la base de datos
include('../BD/ConexionBD.php');

// Recuperar datos del pedido
$id_pedido = $_GET['id_pedido']; // ID del pedido pasado por la URL
$query_pedido = "SELECT * FROM pedido WHERE id_pedido = '$id_pedido'";
$result_pedido = mysqli_query($conn, $query_pedido);
$pedido = mysqli_fetch_assoc($result_pedido);

// Recuperar productos relacionados con el pedido
$query_productos = "SELECT * FROM producto WHERE id_pedido = '$id_pedido'";
$result_productos = mysqli_query($conn, $query_productos);

// Recuperar artículos disponibles
$query_articulos = "SELECT * FROM articulos";
$result_articulos = mysqli_query($conn, $query_articulos);

// Recuperar responsables (usuarios con rol de producción)
$query_responsables = "SELECT p.id_persona, p.nom_persona, p.apellido_paterno FROM persona p
                        JOIN usuario u ON p.id_usuario = u.id_usuario
                        WHERE u.id_rol = 6"; // Rol 'Producción'
$result_responsables = mysqli_query($conn, $query_responsables);

$mensaje = ''; // Variable para el mensaje de resultado
$mensaje_validacion = ''; // Variable para el mensaje de validación

// Arreglo para las cantidades ingresadas
$cantidad_articulo_post = isset($_POST['cantidad_articulo']) ? $_POST['cantidad_articulo'] : [];

if (isset($_POST['validar'])) {
    $cantidad_articulo = $_POST['cantidad_articulo'];
    $validacion_ok = true;

    // Verificar que hay suficientes existencias para cada artículo
    foreach ($cantidad_articulo as $id_articulo => $cantidad) {
        if ($cantidad > 0) {
            // Verificar las existencias actuales
            $query_existencias = "SELECT existencias FROM articulos WHERE id_articulo = '$id_articulo'";
            $result_existencias = mysqli_query($conn, $query_existencias);
            $articulo = mysqli_fetch_assoc($result_existencias);

            if ($cantidad > $articulo['existencias']) {
                $validacion_ok = false;
                $mensaje_validacion = "No hay suficientes existencias para el artículo: " . $articulo['nombre_articulo'];
                break;
            }
        }
    }

    if ($validacion_ok) {
        $mensaje_validacion = "Validación exitosa. Puede proceder a procesar el pedido.";
    }
}

// Procesar pedido y actualizar base de datos al hacer clic en "Procesar Pedido"
if (isset($_POST['procesar'])) {
    $cantidad_articulo = $_POST['cantidad_articulo']; // Esto captura las cantidades del formulario
    $id_productor = $_POST['id_productor'];

    // Actualizar el estado del pedido
    $query_actualizar_estado = "UPDATE pedido SET estatus = 'En preparación' WHERE id_pedido = '$id_pedido'";
    if (!mysqli_query($conn, $query_actualizar_estado)) {
        die('Error al actualizar el estado del pedido: ' . mysqli_error($conn));
    }

    // Asignar el responsable (productor) al pedido
    $query_asignar_responsable = "UPDATE producto SET id_productor = '$id_productor' WHERE id_pedido = '$id_pedido'";
    if (!mysqli_query($conn, $query_asignar_responsable)) {
        die('Error al asignar el responsable al pedido: ' . mysqli_error($conn));
    }

    // Actualizar existencias de artículos según las cantidades enviadas
    foreach ($cantidad_articulo as $id_articulo => $cantidad) {
        if ($cantidad > 0) {
            // Verificar las existencias actuales
            $query_existencias = "SELECT existencias FROM articulos WHERE id_articulo = '$id_articulo'";
            $result_existencias = mysqli_query($conn, $query_existencias);
            $articulo = mysqli_fetch_assoc($result_existencias);

            // Verificar que hay suficientes existencias
            if ($cantidad > $articulo['existencias']) {
                // Si no hay suficientes existencias, mostrar un mensaje de error
                $mensaje = "No hay suficientes existencias para el artículo: " . $articulo['nombre_articulo'];
                break;
            }

            // Restar la cantidad del artículo
            $nuevas_existencias = $articulo['existencias'] - $cantidad;
            $query_actualizar_existencias = "UPDATE articulos SET existencias = $nuevas_existencias WHERE id_articulo = '$id_articulo'";
            if (!mysqli_query($conn, $query_actualizar_existencias)) {
                die('Error al actualizar existencias del artículo: ' . mysqli_error($conn));
            }

            // Insertar en la tabla producto_consumibles
            // Relacionar los artículos consumibles con el producto terminado
            $query_insertar_producto_consumible = "INSERT INTO producto_consumibles (id_articulo, id_producto_t) 
                                                    VALUES ('$id_articulo', '$id_pedido')";
            if (!mysqli_query($conn, $query_insertar_producto_consumible)) {
                die('Error al insertar en producto_consumibles: ' . mysqli_error($conn));
            }
        }
    }

    // Mensaje de éxito
    if (empty($mensaje)) {
        $mensaje = "Pedido procesado exitosamente, responsable asignado y existencias actualizadas.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producción</title>
    <link rel="stylesheet" href="../CSS/menu.css" type="text/css">
    <link rel="stylesheet" href="../CSS/cabecera.css" type="text/css">
    <link rel="stylesheet" href="../CSS/pie_pagina.css" type="text/css">
    <link rel="stylesheet" href="../CSS/tablas_boton.css" type="text/css">
    <link rel="stylesheet" href="../CSS/formularios.css" type="text/css">
    <link rel="stylesheet" href="../CSS/eliminar.css" type="text/css">
    <title>Producción de Pedido</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            color: #000000;
        }
        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section input, .form-section select {
            padding: 8px;
            width: 100%;
            margin-bottom: 10px;
        }
        .form-section button {
            padding: 10px 20px;
            background-color:rgb(69, 131, 160);
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-section button:hover {
            background-color:rgb(91, 161, 194);
        }
        .error-message {
            color: red;
        }
        .success-message {
            color: green;
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
                <li><a href="Usuario.php">Usuarios</a>
                    <ul class="submenu">
                        <li><a href="Registro/Registro_Usuario.php">Alta</a></li>
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
    <h1 class="titulo">Producción</h1>
    <br>
    <h1 class="sub_titulo">Detalles del Pedido #<?php echo $pedido['id_pedido']; ?></h1>
    
    <!-- Formulario con detalles del pedido -->
    <form id="formProduccion" method="POST" action="" class="form_reg_usuario">
        
        <!-- Datos del Pedido -->
        <div class="form-section">
            <h2 class="txt">Datos del Pedido</h2>
            <div class="txt">
            <p><strong>Cliente:</strong> <?php echo $pedido['id_cliente']; ?></p>
            <p><strong>Estatus:</strong> <?php echo $pedido['estatus']; ?></p>
            </div>
        </div>

        <!-- Productos Asociados -->
        <div class="form-section">
            <h2 class="txt">Productos Asociados</h2>
            <table class="tabla_ped">
                <tr>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                </tr>
                <?php while ($producto = mysqli_fetch_assoc($result_productos)): ?>
                <tr>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td>$<?php echo number_format($producto['precio_unitario'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Insumos -->
        <div class="form-section">
            <h2 class="txt">Seleccionar Insumos</h2>
            <table class="tabla_ped">
                <tr>
                    <th>Articulo</th>
                    <th>Cantidad</th>
                </tr>
                <?php while ($articulo = mysqli_fetch_assoc($result_articulos)): ?>
                <tr>
                    <td><?php echo $articulo['nombre_articulo']; ?></td>
                    <td>
                        <input type="number" name="cantidad_articulo[<?php echo $articulo['id_articulo']; ?>]" 
                            value="<?php echo isset($cantidad_articulo_post[$articulo['id_articulo']]) ? $cantidad_articulo_post[$articulo['id_articulo']] : 0; ?>" 
                            min="0" max="<?php echo $articulo['existencias']; ?>">
                        <span>(Existencias: <?php echo $articulo['existencias']; ?>)</span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Mensaje de resultado -->
        <div id="mensajeResultado" class="txt">
            <?php if (isset($mensaje_validacion) && $mensaje_validacion != ''): ?>
                <div class="<?php echo isset($mensaje_validacion) ? 'success-message' : 'error-message'; ?>">
                    <?php echo $mensaje_validacion; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($mensaje) && $mensaje != ''): ?>
                <div class="<?php echo isset($mensaje) ? 'success-message' : 'error-message'; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Seleccionar Responsable -->
        <div class="form-section">
            <h2 class="txt">Seleccionar Responsable (Productor)</h2>
            <select name="id_productor" class="txt">
                <?php while ($responsable = mysqli_fetch_assoc($result_responsables)): ?>
                <option value="<?php echo $responsable['id_persona']; ?>"><?php echo $responsable['nom_persona'] . ' ' . $responsable['apellido_paterno']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Botones de validación y procesamiento -->
        <div class="form-section">
            <button type="submit" name="validar" class="regresar">Validar Existencias</button>
            <button type="submit" name="procesar" class="regresar">Procesar Pedido</button>
      
        </div>
    </form>
    <a href="pro.php" class="regresar">Regresar</a>

</body>
<?php include("../Nav/footer.php"); ?>
</html>
