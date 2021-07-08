<?php
    require '../config.php';
    $envio = new Envio;
    $data = $envio->eliminarEnvio();
    echo $data;
?>