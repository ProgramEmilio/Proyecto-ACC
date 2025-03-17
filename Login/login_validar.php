<?php
include('../BD/ConexionBD.php');
session_start(); // Iniciar sesión para almacenar los datos del usuario

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);

    // Consulta para verificar el usuario
    $sql = "SELECT id_usuario, nombre_usuario, contraseña FROM usuario WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result(); // Almacenar resultado para verificar si hay datos
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_usuario, $nombre_usuario, $hash_password);
            $stmt->fetch();
            
            // DEBUG: Imprimir los valores recuperados en la consola
            echo "<script>console.log('Usuario encontrado: ID = $id_usuario, Nombre = $nombre_usuario, Contraseña Hash = $hash_password');</script>";

            // Verificar si la contraseña es hashada
            if (password_verify($password, $hash_password) || $password === $hash_password) {
                // Guardar el ID del usuario en la sesión
                $_SESSION['id_usuario'] = $id_usuario;
                $_SESSION['nombre_usuario'] = $nombre_usuario;

                header("Location: ../Home/inicio.php");
                exit();
            } else {
                echo "<script>
                        alert('Contraseña incorrecta.\\nCorreo: $correo\\nContraseña ingresada: $password');
                        window.location.href='login.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('No se encontró el usuario con el correo ingresado.\\nCorreo: $correo');
                    window.location.href='login.php';
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error en la consulta SQL.');</script>";
    }
}
?>
