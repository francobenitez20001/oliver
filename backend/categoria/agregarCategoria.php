<?php
    require '../classes/Conexion.php';
    require '../classes/Categoria.php';
    $categoria = new Categoria;
    $bool = $categoria->agregarCategoria();
    echo $bool;
?>