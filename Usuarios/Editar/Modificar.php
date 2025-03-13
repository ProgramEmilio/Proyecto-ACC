<?php
include('../../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta SQL para obtener la información de los usuarios
$sql = "SELECT 
        Usuario.id_usuario, 
        Roles.roles AS Rol, 
        Usuario.nom_usuario AS Nombre, 
        Usuario.ap_pat AS Apellido_Paterno, 
        Usuario.ap_mat AS Apellido_Materno, 
        Usuario.correo AS Correo,
        Usuario.contraseña AS Contraseña, 
        Usuario.RFC AS RFC
    FROM Usuario
    JOIN Roles ON Usuario.id_rol = Roles.id_rol";

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
    <h1 class="titulo">Modificar Usuario</h1>
    
    <?php
    include('../../BD/ConexionBD.php');

    // Asegúrate de que el parámetro 'id_usuario' esté disponible en la URL o en una variable POST.
    $id_usuario = $_GET['id_usuario']; // o $_POST['id_usuario'] dependiendo de cómo se pase el ID

    // Consulta para obtener los datos del usuario
    $sql = "SELECT * FROM Usuario WHERE id_usuario = '$id_usuario'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Obtener los datos del usuario
        $usuario = mysqli_fetch_assoc($result);
    } else {
        echo "Error al obtener los datos del usuario: " . mysqli_error($conn);
    }

    // Obtener los roles disponibles
    $sql_roles = "SELECT * FROM Roles";
    $roles_result = mysqli_query($conn, $sql_roles);

    // Construir las opciones de roles
    $options = '';
    while ($role = mysqli_fetch_assoc($roles_result)) {
        $selected = ($role['id_rol'] == $usuario['id_rol']) ? 'selected' : '';
        $options .= "<option value='" . $role['id_rol'] . "' $selected>" . $role['roles'] . "</option>";
    }

    // Cerrar la conexión
    mysqli_close($conn);
?>

<form class="form_edi_usuario" action="Editar_U.php" method="POST">
    <label for="id_usuario">ID de Usuario:</label>
    <input type="text" id="id_usuario" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>" readonly><br><br>

    <label for="nom_usuario">Nombre de Usuario:</label>
    <input type="text" id="nom_usuario" name="nom_usuario" value="<?php echo $usuario['nom_usuario']; ?>" maxlength="50" required><br><br>

    <label for="ap_pat">Apellido Paterno:</label>
    <input type="text" id="ap_pat" name="ap_pat" value="<?php echo $usuario['ap_pat']; ?>" maxlength="20" required><br><br>

    <label for="ap_mat">Apellido Materno:</label>
    <input type="text" id="ap_mat" name="ap_mat" value="<?php echo $usuario['ap_mat']; ?>" maxlength="20" required><br><br>

    <label for="RFC">RFC:</label>
    <input type="text" id="RFC" name="RFC" value="<?php echo $usuario['RFC']; ?>" maxlength="13"><br><br>

    <label for="correo">Correo Electrónico:</label>
    <input type="text" id="correo" name="correo" value="<?php echo $usuario['correo']; ?>" required><br><br>

    <label for="contraseña">Contraseña:</label>
    <input type="password" id="contraseña" name="contraseña" value="<?php echo $usuario['contraseña']; ?>" maxlength="25" required><br><br>

    <label for="id_rol">Rol:</label>
    <select id="id_rol" name="id_rol" required>
        <option value="">Seleccionar rol</option>
        <?php echo $options; ?>
    </select><br><br>

    <input type="submit" value="Editar Usuario">
</form>
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
