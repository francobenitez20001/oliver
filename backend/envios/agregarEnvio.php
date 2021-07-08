<?php
    require '../config.php';
    $envio = new Envio;
    $data = $envio->agregarEnvio();
    echo $data;
?>