<?php
    require '../classes/Conexion.php';
    require '../classes/ProductosVenta.php';
    $productosVenta = new ProductosVenta();
    $productos = $productosVenta->listarProductosPorVenta();
    echo $productos;
?>