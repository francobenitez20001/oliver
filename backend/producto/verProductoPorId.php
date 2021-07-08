<?php
    require '../config.php';
    $producto = new Producto;
    $data = $producto->verProductoPorId();
    echo $data;
?>