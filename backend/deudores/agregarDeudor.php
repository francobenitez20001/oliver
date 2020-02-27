<?php
    require '../classes/Conexion.php';
    require '../classes/Deudor.php';
    $deudor = new Deudor;
    $data = $deudor->agregarDeudor();
    echo $data;
?>