<?php
include('../BD/ConexionBD.php');


// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : null;
?>
<html>
<head>
    <link rel="stylesheet" href="../CSS/encuesta.css" type="text/css">
</head>
<body>
    <h1 class="titulo">¡Compártenos tu experiencia en Aplica Central Creativa!</h1>
    <form class="form_reg_usuario" action="enviar_encuesta.php" method="POST">
        <input type="hidden" name="id_producto" value="<?php echo isset($_GET['id_producto']) ? htmlspecialchars($_GET['id_producto']) : ''; ?>">
        
        <label for="calificacion">Calificación (1-5):</label>
        <select id="calificacion" name="calificacion" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>

        <label for="comentario">Comentario:</label>
        <textarea id="comentario" name="comentario" rows="4" required></textarea>

        <button class="form_reg_usuario_boton" type="submit">Enviar Comentario</button>
    </form>
</body>
</html>