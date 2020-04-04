<?php
    require '../classes/Conexion.php';
    require '../classes/Usuario.php';
    session_start();
    $user = new Usuario;
    $data = $user->login();
    // if ($data == 'false') {
    //     echo $data;
    // }else{
    //     $objUser = $user->listarUsuario();
    //     echo $objUser;
    // }
    echo $data;
?>