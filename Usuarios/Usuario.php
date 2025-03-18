<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta SQL para obtener la información de los usuarios
$sql = "SELECT 
        usuario.id_usuario,
        roles.roles AS Rol,
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
?>
    <body>

    <h1 class="titulo">Información del Usuario</h1>
    <br>
    <table class='tabla'>
        <thead>
            <tr class='cont'>
                <th scope='col'>ID</th>
                <th scope='col'>Rol</th>
                <th scope='col'>Nombre(s)</th>
                <th scope='col'>Apellido Paterno</th>
                <th scope='col'>Apellido Materno</th>
                <th scope='col'>RFC</th>
                <th scope='col'>Contacto</th>
                <th scope='col'>Usuario</th>
                <th scope='col'>Correo</th>
                <th scope='col'>Contraseña</th>
                <th scope='col'>Editar</th>
                <th scope='col'>Eliminar</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($fila = $result->fetch_assoc()) : ?>
    <tr>
        <th scope='row'><?= htmlspecialchars($fila['id_usuario']) ?></th>
        <td><?= htmlspecialchars($fila['Rol']) ?></td>
        <td><?= htmlspecialchars($fila['Nombre_persona']) ?></td>
        <td><?= htmlspecialchars($fila['Apellido_Paterno']) ?></td>
        <td><?= htmlspecialchars($fila['Apellido_Materno']) ?></td>
        <td><?= htmlspecialchars($fila['RFC']) ?></td>
        <td><?= htmlspecialchars($fila['telefono']) ?></td>
        <td><?= htmlspecialchars($fila['Nombre_usuario']) ?></td>
        <td><?= htmlspecialchars($fila['Correo']) ?></td>
        <td><?= isset($fila['Contraseña']) ? substr($fila['Contraseña'], 0, 2) . '*****' : 'No disponible' ?></td>
        <td><a href='Editar/Modificar.php?id_usuario=<?= htmlspecialchars($fila['id_usuario']) ?>' class='editar'>Editar</a></td>
        <td><a href='Eliminar/Eliminar_Usuario.php?id_usuario=<?= htmlspecialchars($fila['id_usuario']) ?>'>Eliminar</a></td>
    </tr>
    <?php endwhile; ?>
        </tbody>
    </table>
</body>
    <footer class="pie-pagina">
        <div class="grupo-2">
            <small>&copy; 2025 <b>Aplica Central Creativa</b> - Todos los Derechos Reservados.</small>
        </div>
    </footer>

</html>
