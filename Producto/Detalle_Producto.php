<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalles del Producto</title>

    <!-- Fuente Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="detalle_producto.css">
</head>
<body>

    <div class="hm-wrapper">
        <!-- Header -->
        
        <?php include 'nav.php'; ?>

        <!-- Contenedor de los detalles del producto -->
        <div class="product-details-container">
            <div class="product-card" id="producto-detalle">
                <!-- Contenido dinámico del producto -->
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <div class="foo-copy">
                <div class="container">
                    <p>ACC 2025 © Todos los derechos reservados</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Obtener el parámetro 'producto' de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const producto = urlParams.get('producto');
        const productoDetalle = document.getElementById('producto-detalle');

        if (producto) {
            let contenido = "";
            switch (producto) {
                case 'playera':
                    contenido = generarContenido("../Imagenes/playera2.png", "Playera con logo", "Playera de algodón premium con logo estampado de alta calidad.", "$299 MXN");
                    break;
                case 'termo':
                    contenido = generarContenido("../Imagenes/termo3.png", "Termo YETI", "Termo de acero inoxidable con aislamiento térmico de hasta 12 horas.", "$499 MXN");
                    break;
                case 'agenda':
                    contenido = generarContenido("../Imagenes/agenda1.png", "Agenda 2025", "Agenda ejecutiva con cubierta de piel sintética y páginas de papel reciclado.", "$199 MXN");
                    break;
                default:
                    contenido = `<p>Producto no encontrado</p>`;
            }
            productoDetalle.innerHTML = contenido;

            // Elementos para personalización
            const selectPersonalizacion = document.getElementById('personalizacion');
            const nombreInput = document.getElementById('nombre-input');
            const logoInput = document.getElementById('logo-input');
            const buyButton = document.getElementById('buy-button');

            selectPersonalizacion.addEventListener('change', function() {
                nombreInput.style.display = this.value === "nombre" ? "block" : "none";
                logoInput.style.display = this.value === "logo" ? "block" : "none";
            });

            // Redireccionar con parámetros
            buyButton.addEventListener('click', function(e) {
                e.preventDefault();
                let personalizacion = selectPersonalizacion.value;
                let nombre = nombreInput.value.trim();
                let url = `estado-pedido.php?producto=${producto}&personalizacion=${personalizacion}`;

                if (personalizacion === "nombre" && nombre) {
                    url += `&nombre=${encodeURIComponent(nombre)}`;
                }

                window.location.href = url;
            });
        }

        function generarContenido(imagen, titulo, descripcion, precio) {
            return `
                <div class="product-image">
                    <img src="${imagen}" alt="${titulo}">
                </div>
                <div class="product-info">
                    <h1>${titulo}</h1>
                    <p>${descripcion}</p>
                    <p class="price">${precio}</p>

                    <!-- Personalización -->
                    <label for="personalizacion">Personalizar con:</label>
                    <select id="personalizacion" class="custom-select">
                        <option value="ninguno">Sin personalización</option>
                        <option value="logo">Logo</option>
                        <option value="nombre">Nombre</option>
                    </select>

                    <input type="text" id="nombre-input" class="hidden-input" placeholder="Ingresa el nombre" />

                    <input type="file" id="logo-input" class="hidden-input" accept="image/*" />

                    <a href="estado-pedido.php" id="buy-button" class="buy-button">Comprar Ahora</a>
                </div>
            `;
        }
    </script>

</body>
</html>
