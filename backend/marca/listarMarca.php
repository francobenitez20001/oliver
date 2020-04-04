<?php
    require '../classes/Conexion.php';
    require '../classes/Marca.php';
    $marca = new Marca();
    $data = $marca->listarMarca();
    echo $data;
?>