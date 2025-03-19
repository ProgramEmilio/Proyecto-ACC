<?php
include('../BD/ConexionBD.php');

$_servername='localhost:3306';
$database='ACC';
$username='root';
$password='';
//create connection
$conexion=mysqli_connect($_servername,$username,$password,$database);
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Recibir datos del formulario
$nombre_usuario = $_POST['nombre_usuario'];
$correo = $_POST['correo'];
$contraseña = $_POST['contraseña'];
$id_rol = 2; // Valor por default

// Insertar en tabla usuario
$sql = "INSERT INTO usuario (nombre_usuario, correo, contraseña, id_rol)
        VALUES ('$nombre_usuario', '$correo', '$contraseña', $id_rol)";

if ($conexion->query($sql) === TRUE) {
    // Obtener el id_usuario recién insertado
    $id_usuario = $conexion->insert_id;

    // Redirigir al formulario persona y enviar el id_usuario
    header("Location: persona.php?id_usuario=$id_usuario");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conexion->error;
}

$conexion->close();
?>
