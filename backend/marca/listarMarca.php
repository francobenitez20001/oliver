<?php
    require '../config.php';
    $marca = new Marca();
    $data = $marca->listarMarca();
    echo $data;
?>