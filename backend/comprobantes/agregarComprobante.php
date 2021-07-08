<?php
    require '../config.php';
    $comprobante = new Comprobante;
    $data = $comprobante->insertarComprobante();
    echo $data;
?>
