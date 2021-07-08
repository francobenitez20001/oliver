<?php
    require '../config.php';
    $pagoProveedor = new PagoProveedor;
    $bool = $pagoProveedor->actualizarPago();
    echo $bool;
?>