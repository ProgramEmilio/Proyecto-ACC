<?php
include('../../BD/ConexionBD.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $id_persona = $_POST['id_persona'];
    $nom_usuario = $_POST['nombre_usuario'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $id_rol = $_POST['id_rol'];
    $nom_persona = $_POST['nom_persona'];
    $ap_pat = $_POST['apellido_paterno'];
    $ap_mat = $_POST['apellido_materno'];
    $RFC = $_POST['rfc'];
    $telefono = $_POST['telefono'];

    // Generar un ID aleatorio para persona (similar a cómo se hace con id_usuario)
    $id_persona = rand(1000, 9999);

    // Iniciar la transacción
    mysqli_begin_transaction($conn);

    try {
        // Insert en la tabla usuario
        $sql1 = "INSERT INTO usuario (id_usuario, nombre_usuario, correo, contraseña, id_rol) 
                 VALUES ('$id_usuario', '$nom_usuario', '$correo', '$contraseña', '$id_rol')";
        if (!mysqli_query($conn, $sql1)) {
            throw new Exception("Error al insertar en usuario: " . mysqli_error($conn));
        }

        // Insert en la tabla persona
        $sql2 = "INSERT INTO persona (id_persona, id_usuario, nom_persona, apellido_paterno, apellido_materno, rfc, telefono) 
                 VALUES ('$id_persona', '$id_usuario', '$nom_persona', '$ap_pat', '$ap_mat', '$RFC', '$telefono')";
        if (!mysqli_query($conn, $sql2)) {
            throw new Exception("Error al insertar en persona: " . mysqli_error($conn));
        }

        // Si ambas consultas se ejecutan correctamente, se confirma la transacción
        mysqli_commit($conn);
        header("Location: ../Usuario.php");
        exit();
    } catch (Exception $e) {
        // Si hay algún error, se revierte la transacción
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }

    // Cerrar conexión
    mysqli_close($conn);
} else {
    echo 'El formulario no ha sido enviado correctamente.';
    exit();
}
?>
