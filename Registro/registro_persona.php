<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe datos
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $rfc = $_POST['rfc'];
    $codigo_postal = $_POST['codigo_postal'];
    $calle = $_POST['calle'];
    $num_int = $_POST['numero_interior'];
    $num_ext = $_POST['numero_exterior'];
    $colonia = $_POST['colonia'];
    $ciudad = $_POST['ciudad'];
    $telefono = $_POST['telefono'];

    // Prepara SQL
    $sql = "INSERT INTO persona (id_usuario, nom_persona, apellido_paterno, apellido_materno, rfc, codigo_postal, calle, num_int, num_ext, colonia, ciudad, telefono) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Error en prepare: " . $conn->error]);
        exit();
    }

    $stmt->bind_param(
        "isssssssssss",
        $id_usuario,
        $nombre,
        $apellido_paterno,
        $apellido_materno,
        $rfc,
        $codigo_postal,
        $calle,
        $num_int,
        $num_ext,
        $colonia,
        $ciudad,
        $telefono
    );

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        // RESPUESTA JSON CON URL
        echo json_encode(["success" => true, "redirect" => "inicio.php"]);
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Error al insertar: " . $stmt->error]);
        exit();
    }
}
?>
