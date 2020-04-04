<?php
    require '../classes/Conexion.php';
    require '../classes/Marca.php';
    $categoria = new Marca;
    $bool = $categoria->agregarMarca();
    echo $bool;
?>