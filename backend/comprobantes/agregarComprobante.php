<?php
    require '../classes/Conexion.php';
    require '../classes/Comprobante.php';
    $comprobante = new Comprobante;
    $data = $comprobante->insertarComprobante();
    echo $data;
?>
