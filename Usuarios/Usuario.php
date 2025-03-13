<?php
include('../BD/ConexionBD.php');

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../CSS/menu.css" type="text/css">
    <link rel="stylesheet" href="../CSS/cabecera.css" type="text/css">
    <link rel="stylesheet" href="../CSS/pie_pagina.css" type="text/css">
    <link rel="stylesheet" href="../CSS/tablas_boton.css" type="text/css">
    <link rel="stylesheet" href="../CSS/formularios.css" type="text/css">
    <link rel="stylesheet" href="../CSS/departamentos.css" type="text/css">
</head>
<body>
    <header class="cabecera_p">
        <div class="cabecera">
            <h1 class="nom_sis">Aplica Central Creativa</h1>
            <a href="../Menu.php"><img src="../Imagenes/acc_logo.png" class="img-logo" alt="Logo"></a>
            <a href="#"><img src="../Imagenes/avatar.png" class="img-avatar" alt="Avatar"></a>
        </div>
        <div class="header">
        <ul class="nav">
                <!-- Usuarios -->
                <li><a href="Usuario.php">Usuarios</a>
                    <ul class="submenu">
                        <li><a href="Registro/Registro_Usuario.php">Alta</a></li>
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

    <h1 class="titulo">Información del Usuario</h1>
    <br>
    <table class='tabla'>
        <thead>
            <tr class='cont'>
                <th scope='col'>ID</th>
                <th scope='col'>Rol</th>
                <th scope='col'>Nombre</th>
                <th scope='col'>Apellido Paterno</th>
                <th scope='col'>Apellido Materno</th>
                <th scope='col'>RFC</th>
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
        <td><?= htmlspecialchars($fila['Nombre']) ?></td>
        <td><?= htmlspecialchars($fila['Apellido_Paterno']) ?></td>
        <td><?= htmlspecialchars($fila['Apellido_Materno']) ?></td>
        <td><?= htmlspecialchars($fila['RFC']) ?></td>
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
