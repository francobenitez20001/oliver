<?php
    session_start();
    require '../classes/Conexion.php';
    require '../classes/Usuario.php';
    $usuario = new Usuario;
    $data = $usuario->agregarUsuario();
    echo $data;
?>