<?php
include('../BD/ConexionBD.php');

// Obtener lista de productos
$sql_productos = "SELECT * FROM producto";
$productos_result = mysqli_query($conn, $sql_productos);

// Obtener lista de pedidos
$sql_pedidos = "SELECT * FROM pedido";
$pedidos_result = mysqli_query($conn, $sql_pedidos);

// Obtener lista de insumos
$sql_insumos = "SELECT * FROM articulos";
$insumos_result = mysqli_query($conn, $sql_insumos);

// Procesar el formulario cuando se produce el pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];
    $id_pedido = $_POST['id_pedido'];

    // Obtener los consumibles asociados al producto seleccionado
    $sql_consumibles = "SELECT * FROM producto_consumibles WHERE id_producto_t = '$id_producto'";
    $consumibles_result = mysqli_query($conn, $sql_consumibles);

    // Iniciar una transacción para asegurar la consistencia
    mysqli_begin_transaction($conn);
    try {
        // Actualizar el estado del pedido
        $sql_pedido = "UPDATE pedido SET estatus = 'En preparacion' WHERE id_pedido = '$id_pedido'";
        if ($conn->query($sql_pedido) !== TRUE) {
            throw new Exception("Error al actualizar el estado del pedido.");
        }

        // Consumir los artículos correspondientes
        while ($consumible = mysqli_fetch_assoc($consumibles_result)) {
            $id_articulo = $consumible['id_articulo'];
            $sql_articulo = "UPDATE articulos SET existencias = existencias - '$cantidad' WHERE id_articulo = '$id_articulo'";
            if ($conn->query($sql_articulo) !== TRUE) {
                throw new Exception("Error al consumir el artículo con ID: $id_articulo");
            }
        }

        // Confirmar la transacción
        mysqli_commit($conn);

        // Redirigir o mostrar mensaje de éxito
        echo "Pedido producido con éxito.";

    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        mysqli_rollBack($conn);
        echo "Error: " . $e->getMessage();
    }
}

// Cerrar la conexión
mysqli_close($conn);
?>

<!-- Formulario de Producción de Pedido -->
<form action="producir_pedido.php" method="POST">
    <label for="id_producto">Seleccionar Producto:</label>
    <select name="id_producto" id="id_producto" required>
        <option value="">Seleccione un producto</option>
        <?php while ($producto = mysqli_fetch_assoc($productos_result)): ?>
            <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['nombre_producto']; ?> - <?php echo $producto['categoria']; ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="cantidad">Cantidad:</label>
    <input type="number" name="cantidad" id="cantidad" required min="1"><br><br>

    <label for="id_pedido">Seleccionar Pedido:</label>
    <select name="id_pedido" id="id_pedido" required>
        <option value="">Seleccione un pedido</option>
        <?php while ($pedido = mysqli_fetch_assoc($pedidos_result)): ?>
            <option value="<?php echo $pedido['id_pedido']; ?>"><?php echo $pedido['id_pedido']; ?> - <?php echo $pedido['estatus']; ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <input type="submit" value="Producir Pedido">
</form>

<!-- Asignación de insumos -->
<h3>Asignar Insumos a la Producción</h3>
<form action="producir_pedido.php" method="POST">
    <label for="id_producto">Seleccionar Producto para Asignar Insumos:</label>
    <select name="id_producto" id="id_producto" required>
        <option value="">Seleccione un producto</option>
        <?php while ($producto = mysqli_fetch_assoc($productos_result)): ?>
            <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['nombre_producto']; ?> - <?php echo $producto['categoria']; ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="id_articulo">Seleccionar Insumo:</label>
    <select name="id_articulo" id="id_articulo" required>
        <option value="">Seleccione un insumo</option>
        <?php while ($insumo = mysqli_fetch_assoc($insumos_result)): ?>
            <option value="<?php echo $insumo['id_articulo']; ?>"><?php echo $insumo['nombre_articulo']; ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="cantidad_insumo">Cantidad del Insumo:</label>
    <input type="number" name="cantidad_insumo" id="cantidad_insumo" required min="1"><br><br>

    <input type="submit" value="Asignar Insumo a Producción">
</form>

<!-- Listado de productos -->
<h2>Lista de Productos</h2>
<table>
    <thead>
        <tr>
            <th>ID Producto</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio Unitario</th>
            <th>Cantidad</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Volver a consultar los productos en caso de que se necesite mostrar la lista nuevamente
        mysqli_data_seek($productos_result, 0); // Reiniciar el puntero de la consulta
        while ($producto = mysqli_fetch_assoc($productos_result)): ?>
            <tr>
                <td><?php echo $producto['id_producto']; ?></td>
                <td><?php echo $producto['nombre_producto']; ?></td>
                <td><?php echo $producto['categoria']; ?></td>
                <td><?php echo $producto['precio_unitario']; ?></td>
                <td><?php echo $producto['cantidad']; ?></td>
                <td><?php echo $producto['fecha']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
