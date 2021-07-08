<?php
    require '../config.php';
    $venta = new venta();
    $bool = $venta->agregarVenta();
    echo $bool;
?>