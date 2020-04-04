<?php
    require '../classes/Conexion.php';
    require '../classes/Producto.php';
    $producto = new Producto;
    $data = $producto->listarProducto();
    echo $data;
?>