<?php
    require '../config.php';
    $deudor = new Deudor;
    $data = $deudor->eliminarDeudor();
    echo $data;
?>