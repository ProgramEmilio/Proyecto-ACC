<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crear Cuenta</title>

    <!-- Fuente Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="registro.css">

    <script>
        let idUsuario;

        function mostrarPaso2() {
            const usuario = document.getElementById('usuario').value.trim();
            const correo = document.getElementById('correo').value.trim();
            const contrasena = document.getElementById('contraseña').value.trim();

            if (!usuario || !correo || !contrasena) {
                alert('Todos los campos son obligatorios.');
                return;
            }

            fetch('registro_usuario.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `usuario=${encodeURIComponent(usuario)}&correo=${encodeURIComponent(correo)}&contraseña=${encodeURIComponent(contrasena)}`
            })
            .then(response => response.text()) 
            .then(text => {
                console.log("Respuesta del servidor:", text);
                if (text !== "error") {
                    idUsuario = text;  // Guardar el ID del usuario
                    document.getElementById('paso1').style.display = 'none';
                    document.getElementById('paso2').style.display = 'block';
                } else {
                    alert("Error al registrar usuario.");
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error);
                alert("Error en la solicitud.");
            });
        }

        function registrarPersona(event) {
            event.preventDefault();
            
            const formData = new FormData(document.getElementById('paso2'));
            formData.append('id_usuario', idUsuario);
            
            fetch('registro_persona.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    alert('Registro completado exitosamente.');
                    // Redirige al inicio
                    window.location.href = data.redirect;
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <img src="acc_logo.png" alt="Logo">
            </div>
            <h2>Crear Cuenta</h2>
            
            <!-- Paso 1 -->
            <form id="paso1">
                <div class="input-group">
                    <input type="text" id="usuario" required>
                    <label for="usuario">Nombre de Usuario</label>
                </div>
                <div class="input-group">
                    <input type="email" id="correo" required>
                    <label for="correo">Correo Electrónico</label>
                </div>
                <div class="input-group">
                    <input type="password" id="contraseña" required>
                    <label for="contraseña">Contraseña</label>
                </div>
                <button type="button" class="register-button" onclick="mostrarPaso2()">Siguiente</button>
            </form>
            
            <!-- Paso 2 -->
            <form id="paso2" style="display: none;" onsubmit="registrarPersona(event)">
                <div class="input-group">
                    <input type="text" name="nombre" required>
                    <label for="nombre">Nombre</label>
                </div>
                <div class="input-group">
                    <input type="text" name="apellido_paterno" required>
                    <label for="apellido_paterno">Apellido Paterno</label>
                </div>
                <div class="input-group">
                    <input type="text" name="apellido_materno" required>
                    <label for="apellido_materno">Apellido Materno</label>
                </div>
                <div class="input-group">
                    <input type="text" name="rfc" required>
                    <label for="rfc">RFC</label>
                </div>
                <div class="input-group">
                    <input type="text" name="codigo_postal" required>
                    <label for="codigo_postal">Código Postal</label>
                </div>
                <div class="input-group">
                    <input type="text" name="calle" required>
                    <label for="calle">Calle</label>
                </div>
                <div class="input-group">
                    <input type="text" name="numero_interior" required>
                    <label for="numero_interior">Número Interior</label>
                </div>
                <div class="input-group">
                    <input type="text" name="numero_exterior">
                    <label for="numero_exterior">Número Exterior</label>
                </div>
                <div class="input-group">
                    <input type="text" name="colonia" required>
                    <label for="colonia">Colonia</label>
                </div>
                <div class="input-group">
                    <input type="text" name="ciudad" required>
                    <label for="ciudad">Ciudad</label>
                </div>
                <div class="input-group">
                    <input type="tel" name="telefono" required>
                    <label for="telefono">Teléfono</label>
                </div>
                <button type="submit" class="register-button">Crear Cuenta</button>
            </form>
            
            <p class="login-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
        </div>
    </div>

</body>
</html>
