<?php
    require '../config.php';
    $categoria = new Marca;
    $bool = $categoria->agregarMarca();
    echo $bool;
?>