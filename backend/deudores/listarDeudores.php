<?php
    require '../classes/Conexion.php';
    require '../classes/Deudor.php';
    $deudor = new Deudor;
    $data = $deudor->listarDeudor();
    echo $data;
?>