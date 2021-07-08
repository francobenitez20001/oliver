<?php
    require '../config.php';
    $pagoProveedor = new PagoProveedor;
    echo $pagoProveedor->listarPagosPorProveedor();
?>