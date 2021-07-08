<?php
    require '../config.php';
    $envio = new Envio;
    $data = $envio->entregarEnvio();
    echo $data;
?>