<?php
    require '../config.php';
    $envio = new Envio;
    $data = $envio->listarEnvios();
    echo $data;
?>