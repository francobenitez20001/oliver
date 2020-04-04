<?php
    session_start();
    require '../classes/Conexion.php';
    require '../classes/Usuario.php';
    $user = new Usuario;
    $bool = $user->logout();
    echo $bool;
?>