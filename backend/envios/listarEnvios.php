<?php
    require '../classes/Conexion.php';
    require '../classes/Envio.php';
    $envio = new Envio;
    $data = $envio->listarEnvios();
    echo $data;
?>