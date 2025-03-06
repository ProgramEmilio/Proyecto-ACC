<?php

$usuario = $_POST['usuario'];
$contraseña = $_POST['contraseña'];

session_start();

$conn = mysqli_connect('localhost:3307', 'root', '', 'tisistem');

$_SESSION['nombre'] = $nombre_del_usuario;
// Consulta para obtener tanto el Id_usuario como el nombre
$consulta = "SELECT Id_usuario, nombre FROM Usuarios WHERE nombre ='$usuario' AND contraseña ='$contraseña'";
$resultado = mysqli_query($conn, $consulta);
$filas = mysqli_num_rows($resultado);

// Si se encuentra un registro que coincide
if ($filas > 0) {
    $row = mysqli_fetch_assoc($resultado); // Obtener los datos del usuario

    // Guardar tanto el nombre como el Id_usuario en la sesión
    $_SESSION['nombre'] = $row['nombre'];
    $_SESSION['id_usuario'] = $row['Id_usuario'];

    header("Location: Menu.php");
} else {
    // Si la autenticación falla
    include("Login.php");
    echo "<h1>ERROR EN LA AUTENTICACIÓN</h1>";
}

mysqli_free_result($resultado);
mysqli_close($conn);
?>
