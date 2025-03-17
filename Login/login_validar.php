<?php
session_start();

// Conexión a la base de datos
$_servername = 'localhost:3306';
$database = 'ACC';
$username = 'root';
$password_bd = ''; // Cambié el nombre para no confundir

$conexion = mysqli_connect($_servername, $username, $password_bd, $database);

if (!$conexion) {
    die("Error al conectar: " . mysqli_connect_error());
}

// Recibir datos del formulario
$correo = mysqli_real_escape_string($conexion, $_POST['correo']);
$pass = mysqli_real_escape_string($conexion, $_POST['password']);

// Consulta a la base de datos
$sql = "SELECT * FROM usuario WHERE correo = '$correo' AND contraseña = '$pass'";
$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) == 1) {
    $_SESSION['correo'] = $correo;
    header("Location: inicio.php"); // Redirigir a la página de inicio
    exit();
} else {
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='login.php';</script>";
}

mysqli_close($conexion);
?>
