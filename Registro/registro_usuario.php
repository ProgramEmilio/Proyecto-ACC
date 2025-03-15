<?php
include('../BD/ConexionBD.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contraseña']);

    if (empty($usuario) || empty($correo) || empty($contrasena)) {
        die("Error: Todos los campos son obligatorios.");
    }

    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $id_rol = 2; // Rol de usuario normal

    // Verificar si el correo ya está registrado
    $checkQuery = "SELECT id_usuario FROM usuario WHERE correo = ?";
    $stmt = $conn->prepare($checkQuery);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Error: El correo ya está registrado.");
    }
    $stmt->close();

    // Insertar el usuario
    $sql = "INSERT INTO usuario (nombre_usuario, correo, contraseña, id_rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("sssi", $usuario, $correo, $contrasena_hash, $id_rol);

    if ($stmt->execute()) {
        $id_usuario = $stmt->insert_id;
        echo "OK:$id_usuario"; // Enviar solo el ID del usuario creado
    } else {
        die("Error al registrar usuario: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    die("Error: Método no permitido.");
}
?>
