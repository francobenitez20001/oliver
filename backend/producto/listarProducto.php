<?php
    require '../config.php';
    $producto = new Producto;
    $data = $producto->listarProducto();
    echo $data;
?>