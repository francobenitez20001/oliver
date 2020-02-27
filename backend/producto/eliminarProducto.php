<?php
    require '../classes/Conexion.php';
    require '../classes/Producto.php';
    $producto = new Producto;
    $bool = $producto->eliminarProducto();
    echo $bool;
?>