<?php
    require '../config.php';
    $venta = new Venta;
    $data = $venta->getProductoMasVendido($_GET['fecha']);
    echo $data;
?>