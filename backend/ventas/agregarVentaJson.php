<?php
    require '../config.php';
    $venta = new Venta;
    $bool = $venta->agregarVentaJson();
    echo $bool;
?>