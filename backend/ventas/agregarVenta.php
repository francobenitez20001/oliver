<?php
    require '../classes/Conexion.php';
    require '../classes/Venta.php';
    $venta = new venta();
    $bool = $venta->agregarVenta();
    echo $bool;
?>