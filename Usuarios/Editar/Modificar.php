<?php
include('../../BD/ConexionBD.php');
include('../../Nav/header2.php');
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
    <body>
    <h1 class="titulo">Modificar Usuario</h1>
    
    <?php
    include('../../BD/ConexionBD.php');

    // Asegúrate de que el parámetro 'id_usuario' esté disponible en la URL o en una variable POST.
    $id_usuario = $_GET['id_usuario']; // o $_POST['id_usuario'] dependiendo de cómo se pase el ID

    // Consulta para obtener los datos del usuario
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
     JOIN roles ON usuario.id_rol = roles.id_rol
     WHERE usuario.id_usuario = '$id_usuario'";

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
    $selected = ($role['id_rol'] == $usuario['Rol']) ? 'selected' : ''; // Compara con el valor del rol del usuario
    $options .= "<option value='" . $role['id_rol'] . "' $selected>" . $role['roles'] . "</option>";
    }

    // Cerrar la conexión
    mysqli_close($conn);
?>

<form class="form_reg_usuario" action="Editar_U.php" method="POST">
    <label for="id_usuario">ID de Usuario:</label>
    <input type="text" id="id_usuario" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>" readonly><br><br>

    <label for="nom_persona">Nombre(s):</label>
    <input type="text" id="nom_persona" name="nom_persona" value="<?php echo $usuario['Nombre_persona']; ?>" maxlength="50" required><br><br>
    
    <label for="ap_pat">Apellido Paterno:</label>
    <input type="text" id="ap_pat" name="ap_pat" value="<?php echo $usuario['Apellido_Paterno']; ?>" maxlength="20" required><br><br>

    <label for="ap_mat">Apellido Materno:</label>
    <input type="text" id="ap_mat" name="ap_mat" value="<?php echo $usuario['Apellido_Materno']; ?>" maxlength="20" required><br><br>

    <label for="nom_usuario">Nombre de Usuario:</label>
    <input type="text" id="nom_usuario" name="nom_usuario" value="<?php echo $usuario['Nombre_usuario']; ?>" maxlength="50" required><br><br>

    <label for="correo">Correo Electrónico:</label>
    <input type="text" id="correo" name="correo" value="<?php echo $usuario['Correo']; ?>" required><br><br>

    <label for="contraseña">Contraseña:</label>
    <input type="password" id="contraseña" name="contraseña" value="<?php echo $usuario['Contraseña']; ?>" maxlength="25" required><br><br>

    <label for="RFC">RFC:</label>
    <input type="text" id="RFC" name="RFC" value="<?php echo $usuario['RFC']; ?>" maxlength="13"><br><br>

    <label for="telefono">Telefono:</label>
    <input type="text" id="telefono" name="telefono" value="<?php echo $usuario['telefono']; ?>" maxlength="13"><br><br>

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
