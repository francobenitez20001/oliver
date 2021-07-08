<?php
    require '../config.php';
    $comprobante = new Comprobante;
    $data = $comprobante->verComprobantePorProveedor();
    echo $data;
?>