<?php
    require '../classes/Conexion.php';
    require '../classes/ProductosVenta.php';
    $productosVenta = new ProductosVenta();
    $response = $productosVenta->agregarProductos();
    echo $response;
?>