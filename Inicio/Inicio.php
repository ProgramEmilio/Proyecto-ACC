<?php
include('../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

include('../Nav/header.php');
?>
<html>
<body>
    <h1 class="titulo">¡Bienvenido a Aplica Central Creativa!<h1>
        <p class="txt">
        En Aplica Central Creativa, transformamos tus ideas en productos personalizados únicos.
        </p>
        <img src="../Imagenes/acc_logo.png" class="" alt="Logo">
</body>
</html>
<?php
include('../Nav/footer.php');
?>