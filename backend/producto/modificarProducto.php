<?php
    require '../config.php';
    $producto = new Producto;
    $bool = $producto->modificarProducto();
    echo $bool;
?>