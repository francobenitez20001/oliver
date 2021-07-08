<?php
    require '../config.php';
    $deudor = new Deudor;
    $data = $deudor->saldarDeudor();
    echo $data;
?>