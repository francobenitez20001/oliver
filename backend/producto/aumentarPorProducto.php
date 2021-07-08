<?php
    require '../config.php';
    $producto = new Producto;
    $bool = $producto->aumentarPorProducto();
    echo $bool;
?>