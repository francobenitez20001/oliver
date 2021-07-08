<?php
    require '../config.php';
    $producto = new Producto;
    $bool = $producto->aumentarPorMarca();
    echo $bool;
?>