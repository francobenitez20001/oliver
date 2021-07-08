<?php
    require '../config.php';
    $producto =  new Producto;
    $data = $producto->buscarProducto();
    echo $data;
?>