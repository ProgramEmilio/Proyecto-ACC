<?php
include('../../BD/ConexionBD.php');

// Verificar si se ha enviado el ID del usuario a eliminar
if (isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];

    // Verificar si la conexión a la base de datos es exitosa
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Iniciar una transacción
    mysqli_begin_transaction($conn);

    try {
        // SQL para eliminar el usuario en la tabla Persona
        $sql_persona = "DELETE FROM persona WHERE id_usuario = '$id_usuario'";
        if ($conn->query($sql_persona) !== TRUE) {
            throw new Exception("Error al eliminar el registro en la tabla Persona.");
        }

        // SQL para eliminar el usuario en la tabla Usuario
        $sql_usuario = "DELETE FROM Usuario WHERE id_usuario = '$id_usuario'";
        if ($conn->query($sql_usuario) !== TRUE) {
            throw new Exception("Error al eliminar el registro en la tabla Usuario.");
        }

        // Si ambas consultas fueron exitosas, confirmar la transacción
        mysqli_commit($conn);

        // Redirigir a la página de usuarios después de eliminar el usuario
        header("Location: ../Usuario.php");
        exit;

    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        mysqli_rollBack($conn);
        echo "Error al eliminar el usuario: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "ID de usuario no proporcionado.";
}
?>
