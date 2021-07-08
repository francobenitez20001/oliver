<?php
    require '../config.php';
    $producto = new Producto;
    $bool = $producto->aumentarPorProveedor();
    echo $bool;
?>