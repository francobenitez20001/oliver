<?php
    require '../classes/Conexion.php';
    require '../classes/Categoria.php';
    $categoria = new Categoria();
    $data = $categoria->listarCategoria();
    echo $data;
?>