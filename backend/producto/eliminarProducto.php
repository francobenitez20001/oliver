<?php
    require '../config.php';
    $producto = new Producto;
    $bool = $producto->eliminarProducto();
    echo $bool;
?>