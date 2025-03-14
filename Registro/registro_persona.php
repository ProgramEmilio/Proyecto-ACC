<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $rfc = $_POST['rfc'];
    $codigo_postal = $_POST['codigo_postal'];
    $calle = $_POST['calle'];
    $num_ext = $_POST['numero_exterior'];
    $num_int = $_POST['numero_interior'];
    $colonia = $_POST['colonia'];
    $ciudad = $_POST['ciudad'];
    $telefono = $_POST['telefono'];

    // Insertar en la tabla persona
    $sql = "INSERT INTO persona (id_usuario, nom_persona, apellido_paterno, apellido_materno, rfc, codigo_postal, calle, num_ext, num_int, colonia, ciudad, telefono) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssiiiss", $id_usuario, $nombre, $apellido_paterno, $apellido_materno, $rfc, $codigo_postal, $calle, $num_ext, $num_int, $colonia, $ciudad, $telefono);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al registrar los datos personales"]);
    }

    $stmt->close();
    $conn->close();
}
?>
