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
    <h1 class="titulo">Registro de Usuario</h1>
    <script>
    // Función para generar un ID aleatorio
    function generarIdAleatorio() {
        // Generar un número aleatorio entre 1000 y 9999 para usuario
        let idUsuario = Math.floor(Math.random() * 9000) + 1000;
        document.getElementById('id_usuario').value = idUsuario;

        // Generar otro número aleatorio entre 1000 y 9999 para persona
        let idPersona = Math.floor(Math.random() * 9000) + 1000;
        document.getElementById('id_persona').value = idPersona;
    }

    // Ejecutar la función al cargar la página
    window.onload = generarIdAleatorio;
    </script>

    <form class="form_reg_usuario" action="Registrar_U.php" method="POST">
        <label for="id_usuario">ID de Usuario:</label>
        <input type="text" id="id_usuario" name="id_usuario" readonly><br><br>

        <label for="nom_persona">Nombre(s):</label>
        <input type="text" id="nom_persona" name="nom_persona" maxlength="50" required><br><br>

        <label for="apellido_paterno">Apellido Paterno:</label>
        <input type="text" id="apellido_paterno" name="apellido_paterno" maxlength="20" required><br><br>

        <label for="apellido_materno">Apellido Materno:</label>
        <input type="text" id="apellido_materno" name="apellido_materno" maxlength="20" required><br><br>

        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" id="nombre_usuario" name="nombre_usuario" maxlength="50" required><br><br>

        <label for="correo">Correo Electrónico:</label>
        <input type="text" id="correo" name="correo" required><br><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" maxlength="25" required><br><br>

        <label for="rfc">RFC:</label>
        <input type="text" id="rfc" name="rfc" maxlength="13"><br><br>
        
        <label for="telefono">Telefono:</label>
        <input type="text" id="telefono" name="telefono" maxlength="13"><br><br>

        <label for="id_rol">Rol:</label>
        <select id="id_rol" name="id_rol" required>
            <option value="">Seleccionar rol</option>
            <?php echo $options; ?>
        </select><br><br>

        <input type="submit" value="Registrar Usuario">
        <input type="hidden" id="id_persona" name="id_persona">
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
