<?php
    require '../classes/Conexion.php';
    require '../classes/Producto.php';
    $producto =  new Producto;
    $data = $producto->buscarProducto();
    echo $data;
?>