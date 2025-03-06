<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/log.css"> <!-- Archivo CSS externo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Para iconos -->
</head>

<body>
    <form action="Log_Validar.php" method="post">
        <h2>Iniciar Sesión</h2>
        <div class="form-group">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Usuario" name="usuario">
        </div>
        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Contraseña" name="contraseña">
        </div>
        <input type="submit" value="Ingresar">
        
    </form>
</body>

</html>
