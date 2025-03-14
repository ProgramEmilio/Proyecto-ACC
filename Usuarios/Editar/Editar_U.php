<?php
include('../../BD/ConexionBD.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_usuario = $_POST["id_usuario"];
    $nom_usuario = $_POST["nom_usuario"];
    $nom_persona = $_POST["nom_persona"];
    $apellido_paterno = $_POST["ap_pat"];  // Cambié aquí 'apellido_paterno' para que coincida con el nombre del input
    $apellido_materno = $_POST["ap_mat"];  // Cambié aquí 'apellido_materno' para que coincida con el nombre del input
    $RFC = $_POST["RFC"];
    $correo = $_POST["correo"];
    $contraseña = $_POST["contraseña"];
    $telefono = $_POST["telefono"];
    $id_rol = $_POST["id_rol"];

    // Verificar si la conexión a la base de datos es exitosa
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // SQL para actualizar los datos del usuario
    $sql_usuario = "UPDATE usuario 
                    SET nombre_usuario = '$nom_usuario', 
                        correo = '$correo', 
                        contraseña = '$contraseña', 
                        id_rol = '$id_rol' 
                    WHERE id_usuario = '$id_usuario'";

    // SQL para actualizar los datos de la persona
    $sql_persona = "UPDATE persona 
                    SET nom_persona = '$nom_persona', 
                        apellido_paterno = '$apellido_paterno', 
                        apellido_materno = '$apellido_materno', 
                        RFC = '$RFC', 
                        telefono = '$telefono' 
                    WHERE id_usuario = '$id_usuario'"; // Relacionamos ambas tablas por id_usuario

    // Ejecutar la consulta de actualización para usuario
    if ($conn->query($sql_usuario) === TRUE) {
        // Si la actualización del usuario fue exitosa, se actualiza la tabla persona
        if ($conn->query($sql_persona) === TRUE) {
            // Redirigir a la página de usuarios después de actualizar los datos
            header("Location: ../Usuario.php");
            exit;
        } else {
            echo "Error al actualizar la persona: " . $conn->error;
        }
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
