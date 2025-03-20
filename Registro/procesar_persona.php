<?php
include('../BD/ConexionBD.php');

// Recibir datos
$id_usuario = $_POST['id_usuario'];
$nom_persona = $_POST['nom_persona'];
$apellido_paterno = $_POST['apellido_paterno'];
$apellido_materno = $_POST['apellido_materno'];
$rfc = $_POST['rfc'];
$codigo_postal = $_POST['codigo_postal'];
$calle = $_POST['calle'];
$num_int = $_POST['num_int'];
$num_ext = $_POST['num_ext'];
$colonia = $_POST['colonia'];
$ciudad = $_POST['ciudad'];
$telefono = $_POST['telefono'];

// Insertar en tabla persona
$sql = "INSERT INTO persona (id_usuario, nom_persona, apellido_paterno, apellido_materno, rfc, codigo_postal, calle, num_int, num_ext, colonia, ciudad, telefono)
VALUES ('$id_usuario', '$nom_persona', '$apellido_paterno', '$apellido_materno', '$rfc', '$codigo_postal', '$calle', '$num_int', '$num_ext', '$colonia', '$ciudad', '$telefono')";

if ($conn->query($sql) === TRUE) {
    header("Location: inicio.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
