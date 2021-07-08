<?php
    require '../config.php';
    $pagoProveedor = new PagoProveedor;
    $bool = $pagoProveedor->agregarPago();
    echo $bool;
?>