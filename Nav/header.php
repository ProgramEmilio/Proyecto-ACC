<?php
include('conexion.php');

session_start(); // Asegura que se mantenga la sesión
if (!isset($_SESSION['id_rol'])) {
    header("Location:login.php"); // Redirige si no hay sesión activa
    exit();
}
$id_rol = $_SESSION['id_rol'];

// Definir opciones de menú por rol
$menus = [
    1 => [ // Administrador
        "Cliente" => "inicio.php",
        "Usuario" => "Usuario.php",
        "Proveedor" => "Cotizar.php",
        "Ventas" => "#",
        "Compras" => [
            "Solicitar" => "Solicitar_Compra.php",
            "Aprobar" => "../Compras/Aprobar/Aprobar_Compra.php"
        ],
        "Inventario" => "..Inventario.php",
        "Distribución" => "../Distribucion/Distribucion.php",
        "Producción" => "../Produccion/pro.php"
    ],
    2 => ["Cliente"], // Cliente (Sin menú)
    3 => ["Proveedor" => "../Compras/Cotizacion/Cotizar.php"],
    4 => ["Compras" => [
        "Solicitar" => "../Compras/Solicitar/Solicitar_Compra.php",
        "Aprobar" => "../Compras/Aprobar/Aprobar_Compra.php",
    ]],
    5 => ["Ventas" => "#"],
    6 => ["Producción" => "../Produccion/pro.php"], // Producción solo ve producción
    7 => ["Distribución" => "../Distribucion/Distribucion.php"], // Distribuidor solo ve distribución
    8 => ["../Inventario/Inventario.php" => "#"] // Responsable stock solo ve inventario
];
include('CerrarSesion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../CSS/menu.css" type="text/css">
    <link rel="stylesheet" href="../CSS/cabecera.css" type="text/css">
    <link rel="stylesheet" href="../CSS/pie_pagina.css" type="text/css">
    <link rel="stylesheet" href="../CSS/tablas_boton.css" type="text/css">
    <link rel="stylesheet" href="../CSS/formularios.css" type="text/css">
    <link rel="stylesheet" href="../CSS/departamentos.css" type="text/css">
    <link rel="stylesheet" href="../CSS/cabecera2.css" type="text/css">

</head>
<body>
<header class="cabecera_p">
    <div class="cabecera">
        <h1 class="nom_sis">Aplica Central Creativa</h1>
        <a href="../Inicio/inicio.php">
            <img src="../Imagenes/acc_logo.png" class="img-logo" alt="Logo">
        </a>

        <!-- Contenedor para alinear el botón de cierre de sesión -->
        <div class="logout-container">
            <form method="POST">
                <button type="submit" name="cerrar_sesion" class="btn_logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>
        <div class="header">
            <ul class="nav">
                <?php
                // Generar menú dinámicamente según el rol
                if ($id_rol && isset($menus[$id_rol])) {
                    foreach ($menus[$id_rol] as $nombre => $url) {
                        if (is_array($url)) {
                            // Si es un submenú (array), crear un <li> y agregar los subelementos
                            echo "<li><a href='#'>$nombre</a><ul class='submenu'>";
                            foreach ($url as $subnombre => $suburl) {
                                echo "<li><a href='$suburl'>$subnombre</a></li>";
                            }
                            echo "</ul></li>";
                        } else {
                            // Si no es un submenú, simplemente generar el <li> normal
                            echo "<li><a href='$url'>$nombre</a></li>";
                        }
                    }
                } else {
                    echo "<li><a href='#'>Acceso Denegado</a></li>";
                }
                ?>
            </ul>
        </div>
    </header>
</body>
</html>
