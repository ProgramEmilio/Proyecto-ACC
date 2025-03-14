<?php
include('../../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta SQL para obtener la información de los usuarios
$sql = "SELECT 
        usuario.id_usuario,
        usuario.id_rol AS Rol,
        usuario.nombre_usuario AS Nombre_usuario, 
        usuario.correo AS Correo,
        usuario.contraseña AS Contraseña,
        persona.id_persona, 
        persona.nom_persona AS Nombre_persona,
        persona.apellido_paterno  AS Apellido_Paterno, 
        persona.apellido_materno  AS Apellido_Materno, 
        persona.rfc AS RFC,
        persona.telefono AS telefono
    FROM usuario
    JOIN persona ON usuario.id_usuario = persona.id_usuario
    JOIN roles ON usuario.id_rol = roles.id_rol";

$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

$sql1 = "SELECT id_rol, roles FROM Roles";
$result = $conn->query($sql1);

// Generar las opciones del <select>
$options = "";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row['id_rol'] . "'>" . $row['roles'] . "</option>";
    }
} else {
    $options = "<option value=''>No hay roles disponibles</option>";
}

$conn->close();
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
    <title>Modificar </title>
</head>
    <header class="cabecera_p">
        <div class="cabecera">
            <h1 class="nom_sis">Aplica Central Creativa</h1>
            <a href="../Menu.php"><img src="../../Imagenes/acc_logo.png" class="img-logo" alt="Logo"></a>
            <a href="#"><img src="../../Imagenes/avatar.png" class="img-avatar" alt="Avatar"></a>
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
    <h1 class="titulo">Modificar Articulos</h1>
    
    <?php
include('../../BD/ConexionBD.php');

// Asegúrate de que el parámetro 'id_articulo' esté disponible en la URL o en una variable POST.
$id_articulo = $_GET['id_articulo']; // o $_POST['id_articulo'] dependiendo de cómo se pase el ID

// Consulta para obtener los datos del artículo
$sql = "SELECT 
        id_articulo,
        nombre_articulo,
        descripcion,
        categoria,
        precio,
        costo,
        existencias,
        fecha_registro
        FROM articulos
        WHERE id_articulo = '$id_articulo'";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Obtener los datos del artículo
    $articulo = mysqli_fetch_assoc($result);
} else {
    echo "Error al obtener los datos del artículo: " . mysqli_error($conn);
}

// Cerrar la conexión
mysqli_close($conn);
?>

<form class="form_edi_usuario" action="Editar_I.php" method="POST">
    <label for="id_articulo">ID de Artículo:</label>
    <input type="text" id="id_articulo" name="id_articulo" value="<?php echo $articulo['id_articulo']; ?>" readonly><br><br>

    <label for="nombre_articulo">Nombre del Artículo:</label>
    <input type="text" id="nombre_articulo" name="nombre_articulo" value="<?php echo $articulo['nombre_articulo']; ?>" maxlength="70" required><br><br>
    
    <label for="descripcion">Descripción:</label>
    <input type="text" id="descripcion" name="descripcion" value="<?php echo $articulo['descripcion']; ?>" maxlength="50" required><br><br>

    <label for="categoria">Categoría:</label>
    <input type="text" id="categoria" name="categoria" value="<?php echo $articulo['categoria']; ?>" maxlength="50" required><br><br>

    <label for="precio">Precio:</label>
    <input type="text" id="precio" name="precio" value="<?php echo $articulo['precio']; ?>" required><br><br>

    <label for="costo">Costo:</label>
    <input type="text" id="costo" name="costo" value="<?php echo $articulo['costo']; ?>" required><br><br>

    <label for="existencias">Existencias:</label>
    <input type="number" id="existencias" name="existencias" value="<?php echo $articulo['existencias']; ?>" required><br><br>

    <label for="fecha_registro">Fecha de Registro:</label>
    <input type="datetime-local" id="fecha_registro" name="fecha_registro" value="<?php echo date('Y-m-d\TH:i', strtotime($articulo['fecha_registro'])); ?>" required><br><br>

    <input type="submit" value="Editar Artículo">
</form>

<a href="../Articulos.php" class="regresar">Regresar</a>
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
