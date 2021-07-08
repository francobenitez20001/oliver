<?php
    require '../config.php';
    $deudor = new Deudor;
    $data = $deudor->agregarDeudor();
    echo $data;
?>