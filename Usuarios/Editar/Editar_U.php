<?php
include('../../BD/ConexionBD.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_usuario = $_POST["id_usuario"];
    $nom_usuario = $_POST["nom_usuario"];
    $ap_pat = $_POST["ap_pat"];
    $ap_mat = $_POST["ap_mat"];
    $RFC = $_POST["RFC"];
    $correo = $_POST["correo"];
    $contraseña = $_POST["contraseña"];
    $id_rol = $_POST["id_rol"];

    // Verificar si la conexión a la base de datos es exitosa
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // SQL para actualizar los datos del usuario
    $sql = "UPDATE Usuario 
            SET nom_usuario = '$nom_usuario', 
                ap_pat = '$ap_pat', 
                ap_mat = '$ap_mat', 
                RFC = '$RFC', 
                correo = '$correo', 
                contraseña = '$contraseña', 
                id_rol = '$id_rol' 
            WHERE id_usuario = '$id_usuario'";

    // Ejecutar la consulta de actualización
    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página de usuarios después de actualizar los datos
        header("Location: ../Usuario.php");
        exit;
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>