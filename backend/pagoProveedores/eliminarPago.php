<?php
    require '../config.php';
    $pagoProveedor = new PagoProveedor;
    $bool = $pagoProveedor->eliminarPago();
    echo $bool;
?>