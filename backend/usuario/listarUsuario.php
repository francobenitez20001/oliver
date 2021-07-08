<?php
    require '../config.php';
    $usuario = new Usuario;
    $data = $usuario->listarUsuario();
    echo $data;
?>