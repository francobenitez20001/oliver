<?php
    require '../config.php';
    $deudor = new Deudor;
    $data = $deudor->listarDeudor();
    echo $data;
?>