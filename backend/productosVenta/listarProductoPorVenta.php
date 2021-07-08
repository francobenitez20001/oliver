<?php
    require '../config.php';
    $productosVenta = new ProductosVenta();
    $productos = $productosVenta->listarProductosPorVenta();
    echo $productos;
?>