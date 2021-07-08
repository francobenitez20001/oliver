<?php
    require '../config.php';
    $venta = new venta();
    $bool = $venta->agregarVentaJson();
    echo $bool;
?>