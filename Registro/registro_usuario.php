<?php
require 'conexion.php'; // Archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $id_rol = 2; // Supongamos que 2 es el rol de usuario normal

    // Verificar si el correo ya está registrado
    $checkQuery = "SELECT id_usuario FROM usuario WHERE correo = ?";
    $stmt = $conn->prepare($checkQuery);
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Error al preparar la consulta: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "El correo ya está registrado"]);
        exit();
    }
    $stmt->close();

    // Insertar el usuario
    $sql = "INSERT INTO usuario (nombre_usuario, correo, contraseña, id_rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Error al preparar la consulta: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("sssi", $usuario, $correo, $contraseña, $id_rol);

    if ($stmt->execute()) {
        $id_usuario = $stmt->insert_id; 
        echo json_encode(["success" => true, "id_usuario" => $id_usuario]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al registrar usuario: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}
?>
