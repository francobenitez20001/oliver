<?php
    require '../classes/Conexion.php';
    require '../classes/Envio.php';
    $envio = new Envio;
    $data = $envio->entregarEnvio();
    echo $data;
?>