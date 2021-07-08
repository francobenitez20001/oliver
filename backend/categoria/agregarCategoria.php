<?php
    require '../config.php';
    $categoria = new Categoria;
    $bool = $categoria->agregarCategoria();
    echo $bool;
?>