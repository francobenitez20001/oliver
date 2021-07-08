<?php
    require '../config.php';
    $producto = new Producto;
    $bool = $producto->agregarProducto();
    echo $bool;
?>