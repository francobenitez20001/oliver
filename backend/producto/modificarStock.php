<?php
    require '../config.php';
    $producto = new Producto;
    $bool = $producto->modificarStock();
    echo $bool;
?>