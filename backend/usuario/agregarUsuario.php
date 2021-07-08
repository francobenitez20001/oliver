<?php
    require '../config.php';
    $usuario = new Usuario;
    $data = $usuario->agregarUsuario();
    echo $data;
?>