<?php
    include('../../BD/ConexionBD.php');

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_usuario = $_POST['id_usuario'];
        $nom_usuario = $_POST['nom_usuario'];
        $ap_pat = $_POST['ap_pat'];
        $ap_mat = $_POST['ap_mat'];
        $RFC = $_POST['RFC'];
        $correo = $_POST['correo'];
        $contraseña = $_POST['contraseña'];
        $id_rol = $_POST['id_rol'];
    } else {
        echo 'El formulario no ha sido enviado correctamente.';
        exit();
    }

    // Preparar la consulta SQL
    $sql = "INSERT INTO Usuario (id_usuario, nom_usuario, ap_pat, ap_mat, RFC, correo, contraseña, id_rol) 
            VALUES ('$id_usuario', '$nom_usuario', '$ap_pat', '$ap_mat', '$RFC', '$correo', '$contraseña', '$id_rol')";

    // Ejecutar la consulta SQL
    if (mysqli_query($conn, $sql)) {
        header("Location: ../Usuario.php");
        exit();
    } else {
        echo "Error al registrar el Usuario: " . mysqli_error($conn);
    }

    // Cerrar la consulta y la conexión
    $conn->close();
?>