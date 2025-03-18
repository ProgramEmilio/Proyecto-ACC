<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');
?>

<body>

        <!-- =================================
           Productos
        ================================== -->
        <div class="products-container">
            <div class="product">
                <div class="product-img-container">
                    <img src="../Imagenes/playera2.png" alt="Producto 1">
                    <a href="#" class="view-more">Ver más</a>
                </div>
                <p class="product-name">Playera con logo</p>
            </div>
            <div class="product">
                <div class="product-img-container">
                    <img src="../Imagenes/termo3.png." alt="Producto 2">
                    <a href="#" class="view-more">Ver más</a>
                </div>
                <p class="product-name">Termo YETI</p>
            </div>
            <div class="product">
                <div class="product-img-container">
                    <img src="../Imagenes/agenda1.png" alt="Producto 3">
                    <a href="#" class="view-more">Ver más</a>
                </div>
                <p class="product-name">Agenda 2025</p>
            </div>
        </div>
</body>
<?php
include('../Nav/footer.php');
?>

</html>
