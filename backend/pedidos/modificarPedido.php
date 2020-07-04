<?php
    require '../classes/Conexion.php';
    require '../classes/Pedido.php';
    $pedido= new Pedido;
    echo $pedido->modificarPedido();
?>