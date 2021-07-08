<?php
    require '../config.php';
    $categoria = new Categoria();
    $data = $categoria->listarCategoria();
    echo $data;
?>