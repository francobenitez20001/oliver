<?php
    require '../config.php';
    $deudor = new Deudor;
    $data = $deudor->obtenerDeudores();
    echo $data;
?>