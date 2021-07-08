<?php
    require '../config.php';
    $comprobante = new Comprobante;
    $data = $comprobante->verComprobantePorId();
    echo $data;
?>