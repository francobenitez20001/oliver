<?php
    require '../config.php';
    $productosVenta = new ProductosVenta();
    $response = $productosVenta->agregarProductos();
    echo $response;
?>