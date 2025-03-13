<?php
include('../../BD/ConexionBD.php');

// Verificar si se ha enviado el ID del usuario a eliminar
if (isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];

    // Verificar si la conexión a la base de datos es exitosa
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // SQL para eliminar el usuario
    $sql = "DELETE FROM Usuario WHERE id_usuario = '$id_usuario'";

    // Ejecutar la consulta de eliminación
    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página de usuarios después de eliminar el usuario
        header("Location: ../Usuario.php");
        exit;
    } else {
        echo "Error al eliminar el usuario: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "ID de usuario no proporcionado.";
}
?>